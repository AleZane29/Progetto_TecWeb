<!DOCTYPE html>
<html data-theme="light" lang="it">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="../assets/logo_white.png" rel="icon">
  <meta name="description" content="SportLab - Scopri i nostri campi e le discipline sportive: calcio a 5, tennis e basket">
  <meta name="language" content="it">
  <title>Sport - SportLab</title>
  <link href="../style/style.css" rel="stylesheet">
  <link href="../style/servizi.css" rel="stylesheet">
</head>

<body>

  <?php
  include 'components/header.html';
  ?>

  <main id="main-content" tabindex="-1">
    <!-- banner Section -->
    <section class="sport-banner" aria-labelledby="sport-banner-title">
      <div class="sport-banner-container">
        <h1 id="sport-banner-title">I Nostri Sport</h1>
        <p class="sport-banner-subtitle">Campi moderni e attrezzature di qualità per giocare al meglio</p>
      </div>
    </section>

    <!-- Introduzione -->
    <section class="sport-intro" aria-labelledby="intro-title">
      <div class="container">
        <h2 id="intro-title">Una Struttura Completa per Ogni Esigenza</h2>
        <p>
          Al nostro centro non ci si allena solo in sala pesi. Abbiamo tre campi dedicati agli sport di squadra,
          tutti con pavimentazione professionale e illuminazione a LED per giocare anche la sera.
          Puoi prenotare un campo per una partita con gli amici, iscriverti ai corsi oppure unirti ai tornei interni.
        </p>
        <p>
          L'attrezzatura viene controllata regolarmente e negli spogliatoi trovi tutto quello che serve.
          Se preferisci giocare con la tua squadra, nessun problema: accettiamo prenotazioni anche da gruppi esterni.
        </p>
      </div>
    </section>

    <!-- Calcio a 5 -->
    <section class="sport-detail" aria-labelledby="calcio-title">
      <div class="container">
        <article class="sport-card">
          <div class="sport-header">
            <h2 id="calcio-title">Calcio a 5</h2>
            <span class="sport-badge" aria-label="Numero campi disponibili">2 campi</span>
          </div>

          <div class="sport-content">
            <div class="sport-info">
              <h3>Il Campo</h3>
              <p>
                Due campi regolamentari con pavimento in parquet sintetico di ultima generazione.
                Misure standard 40x20 metri, perfetti sia per partite amichevoli che per allenamenti più intensi.
                Le porte sono omologate FIGC e le reti vengono cambiate ogni stagione.
              </p>

              <h3>Attrezzatura Disponibile</h3>
              <ul>
                <li>Palloni da gara e da allenamento (taglia 4)</li>
                <li>Set completo di pettorine colorate per dividere le squadre</li>
                <li>Coni e delimitatori per esercizi specifici</li>
                <li>Portiere mobile per chi vuole allenarsi da solo</li>
              </ul>

              <h3>Informazioni Utili</h3>
              <p>
                I campi si possono prenotare a ore o per abbonamenti mensili. Durante la settimana organizziamo
                anche partite libere dove ci si iscrive singolarmente e noi formiamo le squadre.
                Il mercoledì sera c'è il torneo amatoriale, aperto a tutti i tesserati.
              </p>
              <p>
                <strong>Costo orario:</strong> €60 per campo<br>
                <strong>Abbonamento mensile:</strong> €200 (4 ore a settimana)
              </p>
              <!-- Gallery predefinita per Calcio a 5 -->
              <div class="sport-gallery">
                <h3>Galleria del Campo</h3>
                <div class="gallery-grid" role="region" aria-label="Immagini campo calcio a 5">
                  <figure class="gallery-item">
                    <img src="../assets/soccer1.png" alt="Campo di calcio a 5 con pavimento sintetico e illuminazione" loading="lazy" width="400" height="300">
                    <figcaption>Uno dei nostri due campi regolamentari</figcaption>
                  </figure>
                  <figure class="gallery-item">
                    <img src="../assets/soccer2.png" alt="Palloni da calcio professionali sul campo" loading="lazy" width="400" height="300">
                    <figcaption>Attrezzatura fornita dai nostri centri</figcaption>
                  </figure>
                  <figure class="gallery-item">
                    <img src="../assets/soccer3.png" alt="Giocatori in azione durante una partita di calcio a 5" loading="lazy" width="400" height="300">
                    <figcaption>Partita amichevole tra i nostri soci</figcaption>
                  </figure>
                </div>
              </div>
            </div>
          </div>
        </article>
      </div>
    </section>

    <!-- Tennis -->
    <section class="sport-detail sport-detail-alt" aria-labelledby="tennis-title">
      <div class="container">
        <article class="sport-card">
          <div class="sport-header">
            <h2 id="tennis-title">Tennis</h2>
            <span class="sport-badge" aria-label="Numero campi disponibili">3 campi</span>
          </div>

          <div class="sport-content">
            <div class="sport-info">
              <h3>I Campi</h3>
              <p>
                Tre campi in terra rossa sintetica, con superficie Rebound Ace che ammortizza bene e riduce
                lo stress su ginocchia e caviglie. Sono campi scoperti ma con illuminazione per giocare fino alle 22:00.
                Abbiamo anche una piccola tribuna per chi vuole seguire le partite o aspettare il proprio turno.
              </p>

              <h3>Attrezzatura Disponibile</h3>
              <ul>
                <li>Racchette di diverse grammature (anche per principianti)</li>
                <li>Cesti con palline da allenamento</li>
                <li>Macchina lanciapalle disponibile su prenotazione</li>
                <li>Rete regolamentare con tensionamento professionale</li>
              </ul>

              <h3>Informazioni Utili</h3>
              <p>
                Oltre alla prenotazione libera, teniamo corsi per adulti e ragazzi con il maestro federale Alessandro.
                Le lezioni sono individuali o in piccoli gruppi, massimo quattro persone.
                Una volta al mese organizziamo un torneo sociale, aperto sia ai soci che agli esterni.
              </p>
              <p>
                <strong>Costo orario:</strong> €25 per campo<br>
                <strong>Lezione individuale:</strong> €40 (1 ora)<br>
                <strong>Abbonamento 10 ingressi:</strong> €200
              </p>
              <!-- Gallery predefinita per Tennis -->
              <div class="sport-gallery">
                <h3>Galleria dei Campi da Tennis</h3>
                <div class="gallery-grid" role="region" aria-label="Immagini campi da tennis">
                  <figure class="gallery-item">
                    <img src="../assets/tennis1.png" alt="Campo da tennis in terra rossa sintetica con rete professionista" loading="lazy" width="400" height="300">
                    <figcaption>Uno dei nostri tre campi professionali</figcaption>
                  </figure>
                  <figure class="gallery-item">
                    <img src="../assets/tennis2.png" alt="Racchetta da tennis e pallina su superficie in terra rossa" loading="lazy" width="400" height="300">
                    <figcaption>Attrezzatura fornita: racchette e palline</figcaption>
                  </figure>
                  <figure class="gallery-item">
                    <img src="../assets/tennis3.png" alt="Due giocatori durante una partita di tennis" loading="lazy" width="400" height="300">
                    <figcaption>Partita tra i nostri associati</figcaption>
                  </figure>
                </div>
              </div>
            </div>
          </div>
        </article>
      </div>
    </section>

    <!-- Basket -->
    <section class="sport-detail" aria-labelledby="basket-title">
      <div class="container">
        <article class="sport-card">
          <div class="sport-header">
            <h2 id="basket-title">Basket</h2>
            <span class="sport-badge" aria-label="Numero campi disponibili">1 campo</span>
          </div>

          <div class="sport-content">
            <div class="sport-info">
              <h3>Il Campo</h3>
              <p>
                Un campo coperto regolamentare con parquet professionale della Mondo Sport.
                Dimensioni 28x15 metri, canestri omologati FIP con tabellone trasparente in policarbonato.
                L'illuminazione è a LED e garantisce una visibilità perfetta anche negli orari serali.
              </p>

              <h3>Attrezzatura Disponibile</h3>
              <ul>
                <li>Palloni ufficiali Molten (misura 7 e 6)</li>
                <li>Pettorine per allenamenti e partitelle</li>
                <li>Canestro mobile regolabile in altezza per i più giovani</li>
                <li>Cronometro e tabellone segnapunti elettronico</li>
              </ul>

              <h3>Informazioni Utili</h3>
              <p>
                Il campo viene usato principalmente la sera e nel weekend. C'è anche una squadra amatoriale
                che si allena il martedì e il giovedì: se ti interessa giocare con loro, chiedi in reception.
                Per i ragazzi under 16 organizziamo mini camp durante le vacanze scolastiche.
              </p>
              <p>
                <strong>Costo orario:</strong> €70 per campo<br>
                <strong>Abbonamento mensile:</strong> €240 (4 ore a settimana)
              </p>
              <!-- Gallery predefinita per Basket -->
              <div class="sport-gallery">
                <h3>Galleria del Campo da Basket</h3>
                <div class="gallery-grid" role="region" aria-label="Immagini campo da basket">
                  <figure class="gallery-item">
                    <img src="../assets/basketball1.png" alt="Campo da basket coperto con parquet professionale e illuminazione a LED" loading="lazy" width="400" height="300">
                    <figcaption>Il nostro campo regolamentare coperto</figcaption>
                  </figure>
                  <figure class="gallery-item">
                    <img src="../assets/basketball2.png" alt="Canestro da basket con pallone in volo durante una partita" loading="lazy" width="400" height="300">
                    <figcaption>Canestri omologati FIP professionali</figcaption>
                  </figure>
                  <figure class="gallery-item">
                    <img src="../assets/basketball3.png" alt="Giocatori durante una partita di basket al nostro centro" loading="lazy" width="400" height="300">
                    <figcaption>Azione di gioco tra i nostri associati</figcaption>
                  </figure>
                </div>
              </div>
            </div>
          </div>
        </article>
      </div>
    </section>

    <!-- Mappa della Struttura -->
    <section class="facility-map" aria-labelledby="map-title">
      <div class="container">
        <h2 id="map-title">Mappa della Struttura</h2>
        <p class="map-description">
          Qui sotto troverai la disposizione dei campi e delle varie aree del centro.
          La mappa ti aiuta a orientarti e a capire dove si trova ogni spazio.
        </p>
        <div class="map-placeholder">
          <div class="map-placeholder-content">
            <svg aria-hidden="true" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
              <circle cx="12" cy="10" r="3"></circle>
            </svg>
            <p>Mappa in arrivo</p>
            <span class="map-placeholder-note">Stiamo preparando una mappa dettagliata della struttura</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section" aria-labelledby="cta-title">
      <div class="container">
        <h2 id="cta-title">Prenota il Tuo Campo</h2>
        <p>Contattaci per verificare la disponibilità e prenotare il campo che preferisci.</p>
        <a href="tel:+390123456789" class="cta-primary-button">Chiamaci Ora</a>
      </div>
    </section>
  </main>

  <?php
  include 'components/footer.html';
  ?>

</body>

</html>

<script>
  document.getElementById("navMenuServizi").classList.add("active");
</script>