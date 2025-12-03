<?php
session_start();
?>

<!DOCTYPE html>
<html data-theme="light" lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/logo_white.png" rel="icon">
    <meta name="description" content="SportLab - Presentazione del centro, orari di apertura, informazioni di contatto e annunci speciali">
    <meta name="language" content="it">
    <title>Home - SportLab</title>
    <link href="style/style.css" rel="stylesheet">
</head>

<body>

    <?php
    include 'components/header.html';
    ?>

    <main id="main-content" tabindex="-1">
        <!-- Hero Section con presentazione -->
        <section class="hero" aria-labelledby="hero-title">
            <div class="hero-container">
                <h1 id="hero-title">Benvenuto allo SportLab</h1>
                <p class="hero-subtitle">La tua destinazione per uno stile di vita sano e attivo</p>
            </div>
        </section>

        <!-- Sezione Chi Siamo -->
        <section class="about-section" aria-labelledby="about-title">
            <div class="container">
                <h2 id="about-title">Chi Siamo</h2>
                <p>
                    Il nostro centro sportivo è dedicato a fornire strutture e servizi di qualità per tutte le fasce d'età. 
                    Con più di 20 anni di esperienza nel settore, offriamo programmi di allenamento personalizzati, 
                    corsi collettivi e servizi professionali in un ambiente accogliente e moderno.
                </p>
                <p>
                    La nostra missione è promuovere uno stile di vita sano e attivo, supportando i nostri membri 
                    nel raggiungimento dei loro obiettivi di fitness e benessere.
                </p>
            </div>
        </section>

        <!-- Sezione Orari di Apertura -->
        <section class="hours-section" aria-labelledby="hours-title">
            <div class="container">
                <h2 id="hours-title">Orari di Apertura</h2>
                <div class="hours-grid">
                    <article class="hours-card">
                        <h3>Lunedì - Venerdì</h3>
                        <p><span class="sr-only">Apertura</span>06:00 - 22:00</p>
                    </article>
                    <article class="hours-card">
                        <h3>Sabato</h3>
                        <p><span class="sr-only">Apertura</span>08:00 - 20:00</p>
                    </article>
                    <article class="hours-card">
                        <h3>Domenica</h3>
                        <p><span class="sr-only">Apertura</span>09:00 - 18:00</p>
                    </article>
                </div>
            </div>
        </section>

        <!-- Sezione Informazioni di Contatto -->
        <section class="contact-section" aria-labelledby="contact-title">
            <div class="container">
                <h2 id="contact-title">Contatti</h2>
                <div class="contact-grid">
                    <article class="contact-card">
                        <h3>Telefono</h3>
                        <p><a href="tel:+390123456789">+39 0123 456789</a></p>
                    </article>
                    <article class="contact-card">
                        <h3>Email</h3>
                        <p><a href="mailto:info@centrosportivo.it">info@centrosportivo.it</a></p>
                    </article>
                    <article class="contact-card">
                        <h3>Indirizzo</h3>
                        <p>Via dello Sport, 10<br>00100 Roma (RM)</p>
                    </article>
                </div>
            </div>
        </section>

        <!-- Sezione Posizione della Sede -->
        <section class="location-section" aria-labelledby="location-title">
            <div class="container">
                <h2 id="location-title">Trova la Nostra Sede</h2>
                <p>Siamo convenientemente situati nel cuore della città, facilmente raggiungibile con i mezzi pubblici e con ampio parcheggio disponibile.</p>
                <div class="map-container">
                    <iframe 
                        title="Posizione del centro sportivo su Google Maps"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2970.0440315639997!2d12.496365712347656!3d41.90274097104768!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x132f61a751ec65cd%3A0x1234567890abcdef!2sVia%20dello%20Sport%2C%2010%2C%20Roma!5e0!3m2!1sit!2sit!4v1234567890"
                        width="100%" 
                        height="450" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </section>

        <!-- Sezione Annunci Speciali -->
        <section class="announcements-section" aria-labelledby="announcements-title">
            <div class="container">
                <h2 id="announcements-title">Annunci Speciali</h2>
                <div class="announcements-grid">
                    <article class="announcement-card" role="article">
                        <span class="announcement-badge">SCONTO</span>
                        <h3>Promozione Iniziale - 30% di Sconto</h3>
                        <p>Per i nuovi iscritti nel mese di novembre, usufruisci di uno sconto del 30% sul primo mese di iscrizione.</p>
                        <p class="announcement-date"><span class="sr-only">Data annuncio:</span>Valido fino al 30 novembre 2025</p>
                    </article>

                    <article class="announcement-card" role="article">
                        <span class="announcement-badge">NUOVO</span>
                        <h3>Nuovi Corsi di Yoga</h3>
                        <p>Abbiamo inaugurato una nuova classe di yoga rivolto a tutti i livelli. Lezioni tre volte a settimana con istruttore certificato.</p>
                        <p class="announcement-date"><span class="sr-only">Data annuncio:</span>Disponibile da novembre 2025</p>
                    </article>

                    <article class="announcement-card" role="article">
                        <span class="announcement-badge">EVENTO</span>
                        <h3>Maratona Fitness Autunnale</h3>
                        <p>Partecipa al nostro evento fitness annuale con sessioni di allenamento gratuite, seminari e premi per i partecipanti.</p>
                        <p class="announcement-date"><span class="sr-only">Data evento:</span>Sabato 14 dicembre 2025</p>
                    </article>

                    <article class="announcement-card" role="article">
                        <span class="announcement-badge">SPECIAL</span>
                        <h3>Personal Training - Prima Sessione Gratuita</h3>
                        <p>Scopri il nostro servizio di personal training con una prima sessione di valutazione completamente gratuita. Prenota il tuo slot oggi!</p>
                        <p class="announcement-date"><span class="sr-only">Data annuncio:</span>Offerta disponibile tutto l'anno</p>
                    </article>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="cta-section" aria-labelledby="cta-title">
            <div class="container">
                <h2 id="cta-title">Inizia il Tuo Percorso Oggi</h2>
                <p>Unisciti a centinaia di membri soddisfatti e raggiungi i tuoi obiettivi di fitness.</p>
                <a href="iscrizione.php" class="cta-primary-button">Iscriviti Ora</a>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer" role="contentinfo" aria-label="Piè di pagina">
        <div class="container">
            <p>&copy; 2025 SportLab. Tutti i diritti riservati.</p>
            <ul class="footer-links">
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Termini e Condizioni</a></li>
                <li><a href="#">Accessibilità</a></li>
            </ul>
        </div>
    </footer>

    <script>
        // Skip to main content link accessibilità
        const skipLink = document.querySelector('[href="#main-content"]');
        if (skipLink) {
            skipLink.addEventListener('click', (e) => {
                e.preventDefault();
                const mainContent = document.getElementById('main-content');
                mainContent.focus();
                mainContent.scrollIntoView();
            });
        }
    </script>

</body>

</html>