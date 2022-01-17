<style>
    body{
        font-family: 'gadugi', sans-serif !important;
        font-weight: normal;
        width: 100vw;
        height: 100vh;
        margin: 0;
        padding: 0;
    }

    #entete {
        height: 125px;
        margin: 0 90px 0 100px;
    }

    #logo-interface-container {
        position: relative;
        float: left;
        margin-top: -25px;
    }

    #logo-fonds-container {
        position: relative;
        float: right;
        margin-top: 20px;
        margin-right: -50px;
    }

    #logo-if3n {
        width: 30%;
    }

    #logo-fonds {
        width: 40%;
    }

    #date-haut {
        margin: 50px 90px 0 100px;
        font-size: 22px;
        padding-left: 625px;
    }

    h1 {
        margin-top: 100px;
        font-size: 30px;
        line-height: 20px;
        text-align: center;
    }

    .uppercase {
        text-transform: uppercase;
    }

    #body-text {
        margin: 75px 90px 0 90px;
    }

    p {
        font-size: 20px;
        line-height: 18px;
        text-align: justify;
    }

    #date-bas {
        margin-top: 100px;
    }

    #signature {
        margin: 100px 90px 0 100px;
        font-size: 20px;
        padding-left: 625px;
    }

    #signature-vincianne {
        width: 85%;
    }
</style>

<body>
    <div id="entete">
        <div id="logo-interface-container">
            <img id="logo-if3n" src="./images/Interface3Namur-logo.png" alt="logo-interface3namur">
        </div>
        <div id="logo-fonds-container">
            <img id="logo-fonds" src="./images/logo_FSE-WAL-FWB.jpg" alt="logo-FSE-WAL-FWB">
        </div>
    </div>
    <div id="date-haut">
        Namur, le {{ $date_courte }}
    </div>
    <h1>
        <span class="uppercase">ATTESTATION DE PARTICIPATION &Agrave; LA FORMATION</span><br />
        «{{$formation->nom}}»
    </h1>
    <div id="body-text">
        <p>Je soussign&eacute;e, Vincianne ROUARD, coordinatrice administrative et financi&egrave;re du centre de formation
            Interface3.Namur asbl, atteste que @if($stagiaire->genre === 'homme') Monsieur @elseif($stagiaire->genre === 'femme') Madame @else @endif
            <b>{{ strtoupper($stagiaire->nom) }} {{ $stagiaire->prenom }}</b> suit la formation «{{$formation->nom}}»
            se d&eacute;roulant du {{ $formation->date_debut->isoFormat('D MMMM') }} au {{ $formation->date_fin->isoFormat('D MMMM Y') }}.</p>
        <p>Cette formation dure {{ $formation->nbre_heures }} heures de cours au total. Veuillez trouver ci-joint le programme de formation par jour.</p>
        <p>Je suis &agrave; votre disposition pour tout renseignement compl&eacute;mentaire au 081/63.34.90 ou par mail
            <a href="mailto:v.rouard@interface3namur.be">v.rouard@interface3namur.be</a>.</p>
        <p id="date-bas">Fait à Namur, le {{ $date_longue }}</p>
    </div>
    <div id="signature">
        <img id="signature-vincianne" src="./images/signatures/signature-vincianne.png" alt="signature-vincianne">
        Vincianne Rouard<br />
        Coordinatrice administrative et financi&egrave;re
    </div>
</body>
