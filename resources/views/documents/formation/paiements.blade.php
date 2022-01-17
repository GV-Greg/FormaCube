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

    #container-pair {
        margin-top: 300px;
    }

    #entete {
        margin: 25px 0 0 0;
    }

    .logo {
        width: 25%;
    }

    #logo-interface {
        position: relative;
        float: left;
        margin-top: -25px;
    }

    #container-titre {
        line-height: 10px;
    }

    h1 {
        margin: 100px 0 75px 0;
        font-size: 32px;
        font-weight: bold;
        text-align: center;
    }

    #body-text {
        margin: 0;
        padding: 0;
        font-size: 20px;
        line-height: 18px;
        display: flex;
    }

    #sous-titres-container {
        /*margin-top: 75px;*/
    }

    #infos-container {
        margin-left: 200px;
    }

    .sous-titre {
        font-weight: bold;
    }

    .formateurs {
        margin-top: 25px;
        margin-bottom: 5px;
    }

    #footer {
        margin-top: -75px;
        font-size: 20px;
        line-height: 18px;
        text-align: center;
    }

    .bullet {
        font-size: 25px;
        font-weight: bold;
    }
</style>

<body>
    @for($i=0; $i < count($formation->inscrits); $i++)
        @if($i%2 !== 0)
        <div class="inscritPage">
        @endif
            @if($i%2 !== 0)
                <div id="container-pair">
                @else
                <div id="container-impair">
            @endif
                <div id="entete">
                    <div id="logo-interface">
                        <img src="./images/Interface3-logo.png" alt="logo-interface3namur" class="logo" >
                    </div>
                </div>
                <div id="container-titre">
                    <h1>
                        ATTESTATION DE PAIEMENT
                    </h1>
                </div>
                <div id="body-text">
                    <div id="sous-titres-container">
                        <div class="sous-titre">Module :</div>
                        <div class="sous-titre">Date :</div>
                        <div class="sous-titre">Nom :</div>
                        <div class="sous-titre">Adresse :</div>
                        <div class="sous-titre formateurs">Formateur·rice·s :</div>
                        <div class="sous-titre">Reçu la somme de :</div>
                    </div>
                    <div id="infos-container">
                        <div>{{ $formation->nom }}</div>
                        <div>du {{ $formation->date_debut->format('d/m/Y') }} au {{ $formation->date_fin->format('d/m/Y') }}</div>
                        <div>{{ $formation->inscrits[$i]->nom }} {{ $formation->inscrits[$i]->prenom }}</div>
                        <div>{{ $formation->inscrits[$i]->rue }}, {{ $formation->inscrits[$i]->numero }} {{ $formation->inscrits[$i]->complement }} – {{ $formation->inscrits[$i]->ville->code_postal }} {{ $formation->inscrits[$i]->ville->ville }}</div>
                        <div class="formateurs">. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .</div>
                        <div>{{ $formation->prix }} &euro;</div>
                    </div>
                </div>
                <div id="footer">
                    <b>Interface3.Namur</b> <span class="bullet">&#8226;</span> Avenue Sergent Vrithoff, 2 – 5000 Namur <span class="bullet">&#8226;</span> Tél/Fax : 081/63.34.90 <br />
                    <a href="mailto:contact@interface3namur.be">contact@interface3namur.be</a> <span class="bullet">&#8226;</span>
                    <a href="https://www.interface3namur.be">www.interface3namur.be</a> <span class="bullet">&#8226;</span>
                    <a href="https://www.facebook.com/interface3namur">www.facebook.com/interface3namur</a>
                </div>
            </div>
        @if($i%2 !== 0)
        </div>
        @endif
    @endfor
</body>
