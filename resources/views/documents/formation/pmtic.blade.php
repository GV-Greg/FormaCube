<style>
    @page inscrit {
        margin: 1.9cm;
    }

    .inscritPage {
        page: inscrit;
        page-break-after: always;
    }

    body{
        font-family: 'Arial', sans-serif !important;
        font-weight: normal;
        width: 100vw;
        height: 100vh;
        margin: 0;
        padding: 0;
    }

    header {
        position: fixed;
        top: 45px;
        left: 0;
        right: 0;
        height: 125px;
    }

    main {
        margin-top: 200px;
    }

    .uppercase {
        text-transform: uppercase;
    }

    .bold {
        font-weight: bold;
    }

    #entete {
        margin: 0 70px 0 70px;
    }

    .logo {
        width: 6.5%;
    }

    #logo-wallonie {
        position: relative;
        float: left;
        margin-top: -35px;
    }

    #container-titre {
        line-height: 10px;
    }

    h1 {
        margin-top: 40px;
        font-size: 40px;
        text-align: center;
        text-transform: uppercase;
    }

    #body-text {
        margin: -25px 70px 0 70px;
        font-size: 25px;
    }

    p {
        line-height: 30px;
    }

    .checkbox {
        width: 15px;
        height: 15px;
        top: 5px;
        position: absolute;
        border: 2px solid black;
    }

    .checkbox-content, .sous-checkbox-content {
        position: relative;
        margin-left: 30px;
    }

    .sous-checkboxs-container {
        margin-left: 50px;
    }

    .sous-checkbox-content {
        margin-right: 80px;
    }

    #checkbox-naviguer {
        margin-right: 109px;
    }

    #checkbox-rechercher {
        margin-right: 200px;
    }

    #checkbox-email {
        margin-right: 139px;
    }

    #checkbox-medias {
        margin-right: 156px;
    }

    #checkbox-word {
        margin-right: 41px;
    }

    #checkbox-text {
        margin-right: 140px;
    }

    #checkbox-excel {
        margin-right: 130px;
    }

    #date {
        margin-top: -25px;
        margin-left: 225px;
    }

    #signature {
        margin: 75px 70px 0 250px;
    }

    #signature span {
        margin: 0 125px;
    }
</style>

<body>
@for($i=0; $i < count($formation->inscrits); $i++)
    <div class="inscritPage">
        <header>
            <div id="entete">
                <div id="logo-wallonie">
                    <img src="./images/wallonie.png" alt="logo-wallonie" class="logo">
                </div>
            </div>
        </header>
        <main>
            <div id="container-titre">
                <h1>Attestation de fr&eacute;quentation et des capacit&eacute;s</h1>
            </div>
            <div id="body-text">
                <p>Les soussign&eacute;es Christine DEREYMAEKER, Caroline MARIQUE. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .attestent que</p>
                <p>
                    @if($formation->inscrits[$i]->genre === 'homme') Mr @elseif($formation->inscrits[$i]->genre === 'femme') Mme @else Mr/Mme @endif
                    <span class="uppercase">{{ $formation->inscrits[$i]->nom }}</span> {{ $formation->inscrits[$i]->prenom }}
                    . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .
                </p>
                <p>a suivi une formation informatique au sein d'Interface3.Namur asbl</p>
                <p>
                    et qu'@if($formation->inscrits[$i]->genre === 'homme')il @elseif($formation->inscrits[$i]->genre === 'femme')elle @else il/elle @endif
                    a atteint les capacit&eacute;s requises concernant les unit&eacute;s de formation suivantes (cochez les unit&eacute;s de formation et les parties d'unit&eacute;s concern&eacute;es) :
                </p>
                <div>
                    <div class="bold checkbox-container"><div class="checkbox"></div><div class="checkbox-content">UF1 : l'environnement num&eacute;rique (6 heures)</div></div>
                    <div class="sous-checkboxs-container">
                        <span class="sous-checkbox-container"><div class="checkbox"></div><span class="sous-checkbox-content">La machine</span></span>
                        <span class="sous-checkbox-container"><div class="checkbox"></div><span class="sous-checkbox-content">Syst&egrave;me d'exploitation</span></span>
                        <span class="sous-checkbox-container"><div class="checkbox"></div><span class="sous-checkbox-content">Tablettes</span></span>
                    </div>
                    <div class="bold checkbox-container"><div class="checkbox"></div><div class="checkbox-content"> UF2 : la recherche et le stockage (8 heures)</div></div>
                    <div class="sous-checkboxs-container">
                        <span class="sous-checkbox-container"><div class="checkbox"></div><span id="checkbox-naviguer" class="sous-checkbox-content">Naviguer</span></span>
                        <span class="sous-checkbox-container"><div class="checkbox"></div><span id="checkbox-rechercher" class="sous-checkbox-content">Rechercher</span></span>
                        <span class="sous-checkbox-container"><div class="checkbox"></div><span class="sous-checkbox-content">Organiser et stocker</span></span>
                    </div>
                    <div class="bold checkbox-container"><div class="checkbox"></div><div class="checkbox-content"> UF3 : la communication (8 heures)</div></div>
                    <div class="sous-checkboxs-container">
                        <span class="sous-checkbox-container"><div class="checkbox"></div><span id="checkbox-email" class="sous-checkbox-content">E-mail</span></span>
                        <span class="sous-checkbox-container"><div class="checkbox"></div><span id="checkbox-medias" class="sous-checkbox-content">M&eacute;dias sociaux</span></span>
                        <span class="sous-checkbox-container"><div class="checkbox"></div><span class="sous-checkbox-content">Partage</span></span>
                    </div>
                    <div class="bold checkbox-container"><div class="checkbox"></div><div class="checkbox-content"> UF4 : la cr&eacute;ation (26 heures)</div></div>
                    <div class="sous-checkboxs-container">
                        <span class="sous-checkbox-container"><div class="checkbox"></div><span id="checkbox-word" class="sous-checkbox-content">Microsoft Word</span></span>
                        <span class="sous-checkbox-container"><div class="checkbox"></div><span id="checkbox-text" class="sous-checkbox-content">Open Office Text</span></span>
                        <span class="sous-checkbox-container"><div class="checkbox"></div><span id="checkbox-excel" class="sous-checkbox-content">Microsoft Excel</span></span>
                        <span class="sous-checkbox-container"><div class="checkbox"></div><span class="sous-checkbox-content">Open Office Calc</span></span>
                    </div>
                </div>
                <p>Cette formation a &eacute;t&eacute; dispens&eacute;e dans le cadre du d&eacute;cret du 3 f&eacute;vrier 2005 sur le plan mobilisateur des technologies de l'information et de la communication &ndash; <span class="bold">le P.M.T.I.C., promu et financ&eacute; par la Wallonie.</span></p>
            </div>
            <div id="date">
                <p>&Agrave; Namur, le {{ $today->isoformat('d MMMM Y') }}</p>
            </div>
            <div id="signature">
                <span>La responsable de l'organisme</span>
                <span>La responsable p&eacute;dagogique</span>
            </div>
        </main>
    </div>
@endfor
</body>
