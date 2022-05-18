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
        height: 100px;
    }

    #logo {
        width: 30%;
        position: relative;
        float: left;
        margin-top: -25px;
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
        line-height: 28px;
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
</style>

<body>
    @for($i=0; $i < count($formation->inscrits); $i++)
        <div class="inscritPage">
            <header>
                <div id="entete">
                    <div id="logo">
                        <img src="./storage/images/logos/Logo_FormaCube-Noir-500x100.png" alt="logo-FormaCube">
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
                            &agrave; {{ $formation->inscrits[$i]->ville->code_postal }} {{ $formation->inscrits[$i]->ville->ville }}</b>) au lieu de formation «FormaCube» :</p>
                    <p>Nombre de kilom&egrave;tres :  . . . . . kms</p>
                    <p id="compte">Num&eacute;ro de compte bancaire : BE . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .</p>

                </div>
                <div id="date">
                    <p>Fait à Namur, le . . . . / . . . . / . . . .</p>
                </div>
                <div id="signature">
                    <p>Signature pr&eacute;c&eacute;d&eacute;e de la mention manuscrite</p>
                    <p id="certification">« Certifi&eacute; sinc&egrave;re et exact »</p>
                </div>
                <div id="identite">
                    <p>Nom et pr&eacute;nom : . . . . . . . . . . . . . . . . . . . . . . . . . . . .</p>
                </div>
            </main>
        </div>
    @endfor
</body>
