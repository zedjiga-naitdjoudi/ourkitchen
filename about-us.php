<?php
$title = "About Us";
require "./include/mainheader.inc.php";
?>
<!-- Contenu principal de la page "About Us" -->
<div class="about-us-content">
    <h1>About Us</h1>
    
    <!-- Introduction générale -->
    <section class="team-introduction">
        <h2 class="sr-only">Our Story</h2> <!-- Titre ajouté, mais caché visuellement -->
        <p>We are a passionate team of Computer Science students from the University CY Cergy Paris. Currently in our 3rd year of the Bachelor's degree in Computer Science (L3), we decided to create this website as part of our academic project.</p>
        <p>Our team is composed of three dynamic and motivated members:</p>
    </section>

    <!-- Présentation des membres de l'équipe -->
    <section class="team-members">
        <h2 class="sr-only">Meet the Team</h2> <!-- Titre ajouté, mais caché visuellement -->
        <div class="team-member">
            <img src="ressources/zj.webp" alt="NAIT DJOUDI Zedjiga" class="team-photo">
            <h3>NAIT DJOUDI Zedjiga</h3>
            <p>As a Computer Science enthusiast, I focus on web development and databases. I enjoy solving problems and finding innovative solutions through code. My goal is to create impactful web applications.</p>
        </div>
        <div class="team-member">
            <img src="ressources/ame.webp" alt="BOUKRI Amelia" class="team-photo">
            <h3>BOUKRI Amelia</h3>
            <p>I'm passionate about algorithms and software engineering. I have a strong interest in artificial intelligence and its application in real-world scenarios. I aim to contribute to the development of intelligent systems.</p>
        </div>
        <div class="team-member">
            <img src="ressources/sar.webp" alt="SADADOU Sarah" class="team-photo">
            <h3>SADADOU Sarah</h3>
            <p>I am fascinated by front-end technologies and user experience design. My focus is on creating seamless and engaging interfaces for web applications. I am always looking to improve user interactions and interfaces.</p>
        </div>
    </section>

    <!-- Section sur l'université -->
    <section class="university-info">
        <h2 class="sr-only">Our University</h2> <!-- Titre ajouté, mais caché visuellement -->
        <p>We are proud to be students at CY Cergy Paris University, an institution that fosters innovation and excellence in the field of Computer Science. Our courses provide us with the tools and knowledge necessary to succeed in the tech industry.</p>
    </section>

</div>

<?php
require "./include/footer.inc.php"; // Inclure le footer
?>
