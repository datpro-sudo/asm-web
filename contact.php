<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Jewelry Store</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f5faff;
        color: #333;
    }

    .container {
        max-width: 800px;
        margin: 30px auto;
        padding: 20px;
        background: #ffffff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .header a {
        text-decoration: none;
        font-size: 16px;
        color: #007bff;
        font-weight: bold;
        border: 1px solid #007bff;
        padding: 8px 12px;
        border-radius: 4px;
        transition: background-color 0.3s, color 0.3s;
    }

    .header a:hover {
        background-color: #007bff;
        color: #fff;
    }

    h1 {
        text-align: center;
        color: #0056b3;
    }

    .info p {
        margin: 8px 0;
        color: #333;
    }

    form {
        display: flex;
        flex-direction: column;
    }

    input,
    textarea,
    button {
        margin-bottom: 10px;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
    }

    button {
        background-color: #007bff;
        color: #fff;
        font-weight: bold;
        cursor: pointer;
        border: none;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #0056b3;
    }

    .socials a {
        margin-right: 10px;
        text-decoration: none;
        color: #007bff;
    }

    iframe {
        width: 100%;
        border: 0;
        margin-top: 20px;
        border-radius: 8px;
    }

    .socials {
        margin: 20px 0;
    }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header with Back to Home -->
        <div class="header">
            <a href="home.php">Back to Home</a>
            <h1>Contact Us</h1>
        </div>
        <!-- Contact Information -->
        <div class="info">
            <p><strong>Address:</strong> 123 Le Loi Street, District 1, Ho Chi Minh City</p>
            <p><strong>Phone:</strong> +84 909 123 456</p>
            <p><strong>Email:</strong> support@jewelrystore.com</p>
        </div>
        <!-- Contact Form -->
        <form>
            <input type="text" placeholder="Your Name" required>
            <input type="email" placeholder="Your Email" required>
            <input type="tel" placeholder="Phone Number (optional)">
            <textarea placeholder="Your Message" rows="5" required></textarea>
            <button type="submit">Send</button>
        </form>
        <!-- Social Media Links -->
        <h3>Follow Us</h3>
        <div class="socials">
            <a href="#">Facebook</a>
            <a href="#">Instagram</a>
            <a href="#">Zalo</a>
        </div>
        <!-- Google Map -->
        <h3>Find Us Here</h3>
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.8789357688993!2d105.77698931493215!3d21.036237685994254!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135abcf6e9c5b97%3A0x2d4b5cbff78945e!2zTmjDoCBDxrDGoW5nIFRoYW5oIFTDom4gQ8O0bmcgSOG7k25n!5e0!3m2!1sen!2s!4v1670929621369!5m2!1sen!2s"
            allowfullscreen="" loading="lazy">
        </iframe>
    </div>
</body>

</html>