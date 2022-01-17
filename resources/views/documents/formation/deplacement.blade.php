<style>
    @page inscrit {
        size: A4 portrait;
        margin: 2cm;
    }

    .inscritPage {
        page: inscrit;
        page-break-after: always;
    }

    body{
        font-family: 'gadugi', sans-serif !important;
        font-weight: normal;
        width: 100vw;
        height: 100vh;
        margin: 0;
        padding: 0;
    }

    header {
        position: fixed;
        top: 50px;
        left: 0;
        right: 0;
        height: 125px;
    }

    main {
        margin-top: 200px;
    }

    footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        height: 50px;
        font-size: 20px;
        text-align: center;
        line-height: 18px;
        letter-spacing: -1px;
    }

    #entete {
        margin: 0 90px 0 100px;
    }

    .logo {
        width: 30%;
    }

    #logo-interface {
        position: relative;
        float: left;
        margin-top: -35px;
    }

    #logo-pouvsub {
        position: relative;
        float: right;
        margin-top: -10px;
        margin-right: -50px;
    }

    #container-titre {
        line-height: 10px;
    }

    h1 {
        margin-top: 100px;
        font-size: 30px;
        font-weight: bold;
        text-align: center;
    }

    #sous-titre {
        text-align: center
    }

    #body-text {
        margin: 75px 90px 0 90px;
    }

    p {
        font-size: 25px;
        line-height: 23px;
        text-align: justify;
    }

    #compte {
        margin-top: 25px;
    }

    #date, #signature, #identite {
        margin-right: 90px;
        margin-left: 430px;
        font-size: 25px;
    }

    #date {
        margin-top: 120px;
    }

    #signature {
        margin-top: -20px;
        line-height: 15px;
    }

    #certification {
        margin-top: -25px;
        font-weight: bold;
    }

    #identite {
        margin-top: 120px;
    }

    .bullet {
        font-size: 25px;
        font-weight: bold;
    }
</style>

<body>
    @for($i=0; $i < count($formation->inscrits); $i++)
        <div class="inscritPage">
            <header>
                <div id="entete">
                    <div id="logo-interface">
                        <img src="./images/Interface3Namur-logo.png" alt="logo-interface3namur" class="logo" >
                    </div>
                    <div id="logo-pouvsub">
                        <img src="{{ './images/logos/' . $pouvsub->logo }}" alt="logo-pouvoir-subsidiant" class="logo">
                    </div>
                </div>
            </header>
            <main>
                <div id="container-titre">
                    <h1>
                        « {{$formation->nom}} »
                    </h1>
                    <div id="sous-titre">Projet du : {{ $formation->date_debut->isoFormat('DD/MM/YY') }} au {{ $formation->date_fin->isoFormat('DD/MM/YY') }}</div>
                </div>
                <div id="body-text">
                    <p>Je, soussign&eacute;e, <b>{{ strtoupper($formation->inscrits[$i]->nom) }} {{ $formation->inscrits[$i]->prenom }}</b>, déclare exact le nombre de kilom&egrave;tres s&eacute;parant
                        mon domicile (<b>{{ $formation->inscrits[$i]->rue }}, {{ $formation->inscrits[$i]->numero }} {{ $formation->inscrits[$i]->boite }}
                            &agrave; {{ $formation->inscrits[$i]->ville->code_postal }} {{ $formation->inscrits[$i]->ville->ville }}</b>) au lieu de formation «Interface3.Namur»
                        (Avenue Sergent Vrithoff, 2 - 5000 Namur):</p>
                    <p>Nombre de kilom&egrave;tres :  . . . . . kms</p>
                    <p id="compte">Num&eacute;ro de compte bancaire : BE . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .</p>

                </div>
                <div id="date">
                    <p>Fait à Namur, le . . . . / . . . . / . . . .</p>
                </div>
                <div id="signature">
                    <p>Signature pr&eacute;c&eacute;d&eacute;e de la mention manuscrite</p>
                    <p id="certification">« Certifi&eacute; sinc&egrave;re et exact »</p>
                </div>
                <div id="identite">
                    <p>Nom et pr&eacute;nom : . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .</p>
                </div>
            </main>
            <footer>
                <b>Interface3.Namur</b> <span class="bullet">&#8226;</span> Avenue Sergent Vrithoff, 2 – 5000 Namur <span class="bullet">&#8226;</span> N° d’entreprise BE0870 417226<br />
                Tél / Fax : 081/63 34 90 <span class="bullet">&#8226;</span> contact@interface3namur.be <span class="bullet">&#8226;</span> www.interface3namur.be
            </footer>
        </div>
    @endfor
</body>
