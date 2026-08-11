<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Warisan Norman - Premium Catering</title>
        <link rel="stylesheet" href="frontPageStyle.css">

    </head>
    <body>
        <?php include 'components/navbar/navbar.php'; ?>

        <section class="frontText">
            <div class="content">
                <h1>WELCOME<br>
                    TO<br>
                    WARISAN NORMAN
                </h1>
                <p>PREMIUM CATERING, <br> EFFORTLESSLY BOOKED.</p>
            </div>
            <div class="shapes">
                <span></span>
                <span></span>
            </div>
        </section>
        
        <script>
            const hamburger = document.getElementById('hamburger');
            const rightSection = document.getElementById('right-section');

            hamburger.addEventListener('click', () => {
                rightSection.classList.toggle('active');
                hamburger.classList.toggle('open'); // Morphs the horizontal bars to 'X'
            });
        </script>
    </body>
</html>

