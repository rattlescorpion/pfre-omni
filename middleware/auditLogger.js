const db = require('../db');



const auditLogger = async (req, res, next) => {

    const { method, url, body, user } = req;

    

    // Capture the original res.json to log the response

    const oldJson = res.json;

    res.json = function (data) {

        if (['POST', 'PUT', 'DELETE'].includes(method)) {

            db.query(

                'INSERT INTO audit_logs (user_id, action, entity_type, new_values, ip_address) VALUES ($1, $2, $3, $4, $5)',

                [user?.id || 1, method, url, JSON.stringify(body), req.ip]

            );

        }

        oldJson.apply(res, arguments);

    };

    next();

};



module.exports = auditLogger;