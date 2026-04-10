static async promoteToUser(contactId, password) {
    const contact = await Contact.findById(contactId);
    
    // 1. Create entry in 'users' table
    const user = await User.create({
        name: `${contact.first_name} ${contact.last_name}`,
        email: contact.email,
        password: hashedPassword
    });

    // 2. Link back to contact
    contact.is_user = true;
    contact.user_id = user.id;
    await contact.save();
    
    return user;
}