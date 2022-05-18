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
        height: 100px;
    }

    #logo {
        width: 30%;
        position: relative;
        float: left;
        margin-top: -25px;
    }

    h1 {
        margin-top: 100px;
        font-size: 30px;
        line-height: 30px;
        text-align: center;
    }

    #date-haut {
        margin: 50px 90px 0 100px;
        font-size: 22px;
        padding-left: 625px;
    }

    .uppercase {
        text-transform: uppercase;
    }

    #body-text {
        margin: 75px 90px 0 90px;
    }

    p {
        font-size: 20px;
        line-height: 20px;
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
</style>

<body>
    <div id="entete">
        <div id="logo">
            <img src="./storage/images/logos/Logo_FormaCube-Noir-500x100.png" alt="logo-FormaCube">
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
        <p>Par la pr&eacute;sente, «FormaCube» attestons que @if($stagiaire->genre === 'homme') Monsieur @elseif($stagiaire->genre === 'femme') Madame @else @endif
            <b>{{ strtoupper($stagiaire->nom) }} {{ $stagiaire->prenom }}</b> suit la formation «{{$formation->nom}}»
            se d&eacute;roulant du {{ $formation->date_debut->isoFormat('D MMMM') }} au {{ $formation->date_fin->isoFormat('D MMMM Y') }}.</p>
        <p>Cette formation dure {{ $formation->nbre_heures }} heures de cours au total. Veuillez trouver ci-joint le programme de formation par jour.</p>
        <p>Nous sommes &agrave; votre disposition pour tout renseignement compl&eacute;mentaire au 081/00.00.00 ou par mail
            <a href="mailto:demo@creacube.be">demo@formacube.be</a>.</p>
        <p id="date-bas">Fait à Namur, le {{ $date_longue }}</p>
    </div>
    <div id="signature">
        «FormaCube»
    </div>
</body>
