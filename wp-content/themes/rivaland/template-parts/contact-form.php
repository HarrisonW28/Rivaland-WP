<?php
/**
 * Contact Form Template Part
 * 
 * @package Rivaland
 */

$form_action = get_field('form_action') ?? '#';
?>

<form action="<?php echo esc_url($form_action); ?>" method="post" class="contact-form">
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" required>
    </div>
    
    <div class="form-group">
        <label for="telephone">Telephone</label>
        <input type="tel" id="telephone" name="telephone">
    </div>
    
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
    </div>
    
    <div class="form-group">
        <label for="message">Message</label>
        <textarea id="message" name="message" rows="5" required></textarea>
    </div>
    
    <button type="submit" class="button button--primary">Send message</button>
</form>

