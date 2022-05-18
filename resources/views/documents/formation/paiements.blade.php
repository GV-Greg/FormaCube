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
        margin: 100px 0 75px 0;
        font-size: 32px;
        font-weight: bold;
        text-align: center;
    }

    #body-text {
        margin: 0;
        padding: 0;
        font-size: 20px;
        line-height: 40px;
    }

    #sous-titres-container {
        /*width: 200px;*/
    }

    .sous-titre {
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
                    <div id="logo">
                        <img src="./storage/images/logos/Logo_FormaCube-Noir-500x100.png" alt="logo-FormaCube">
                    </div>
                </div>
                <div id="container-titre">
                    <h1>
                        ATTESTATION DE PAIEMENT
                    </h1>
                </div>
                <div id="body-text">
                    <div id="sous-titres-container">
                        <span class="sous-titre">Formation :</span> «{{ $formation->nom }}»<br/>
                        <span class="sous-titre">Date :</span> du {{ $formation->date_debut->format('d/m/Y') }} au {{ $formation->date_fin->format('d/m/Y') }}<br/>
                        <span class="sous-titre">Nom :</span> {{ $formation->inscrits[$i]->nom }} {{ $formation->inscrits[$i]->prenom }}<br/>
                        <span class="sous-titre">Adresse :</span> {{ $formation->inscrits[$i]->rue }}, {{ $formation->inscrits[$i]->numero }} {{ $formation->inscrits[$i]->complement }} – {{ $formation->inscrits[$i]->ville->code_postal }} {{ $formation->inscrits[$i]->ville->ville }}<br/>
                        <span class="sous-titre">Reçu la somme de :</span> {{ $formation->prix }} &euro;<br/>
                    </div>
                </div>
            </div>
        @if($i%2 !== 0)
        </div>
        @endif
    @endfor
</body>
