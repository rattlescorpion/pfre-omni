router.post('/', auditLogger, async (req, res) => {
    const { name, phone, budget, locality } = req.body;

    try {
        // 1. Check for Duplicates (Enterprise Rule)
        const duplicate = await db.query('SELECT id FROM leads WHERE phone = $1', [phone]);
        if (duplicate.rows.length > 0) {
            return res.status(400).json({ error: 'Lead already exists' });
        }

        // 2. Insert Lead
        const result = await db.query(
            'INSERT INTO leads (name, phone, budget, locality, status) VALUES ($1, $2, $3, $4, $5) RETURNING *',
            [name, phone, budget, locality, 'new']
        );

        // 3. Trigger Auto-Assignment (Round Robin)
        // logic for assigning to the next active sales employee...
        
        res.json(result.rows[0]);
    } catch (err) {
        res.status(500).send(err.message);
    }
});