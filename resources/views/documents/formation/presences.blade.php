<style type="text/css">
    body{
        font-family: 'gadugi', sans-serif !important;
        font-weight: normal;
        width: 100vw;
        height: 100vh;
        margin: 0;
        padding: 0;
    }

    #entete {
        height: 150px;
    }

    .logo {
        width: 30%;
    }

    #logo-interface {
        position: relative;
        float: left;
    }

    #logo-pouvsub {
        position: relative;
        float: right;
        margin-top: -35px;
        margin-right: -50px;
    }

    h3 {
        width: 100vw;
        height: 50px;
        margin: -20px 0 -40px 0;
        padding: 0;
        font-family: 'blogger', sans-serif !important;
        font-weight: bold;
        color: #00768C;
        font-size: 35px;
    }

    #infos-formation {
        margin: 0 0 20px 0;
        padding: 0;
        font-size: 25px;
        font-weight: bold;
        height: 50px;
    }

    h4 {
        margin-top: 5px;
        margin-bottom: 5px;
    }

    table {
        margin: 0;
        padding: 0;
        width: 100%;
        border-collapse: collapse;
        border: 0.1em solid black;
    }

    table th {
        font-weight: bold;
        font-size: 12pt;
        line-height: 10pt;
        border: 0.1em solid black;
    }

    td {
        border: 1px solid black;
        height: 45px;
        padding: 0.37em;
    }

    #table-stagiaires {
        margin-top: 15px;
    }

    #table-formateur {
        margin-top: -5px;
    }

    #nomprenom {
        width: 250px;
    }

    .temps {
        width: 75px;
    }

    #arrivee {
        width: 125px;
    }

    .auto {
        width: auto;
    }

    .infos {
        font-size: 20px;
    }

    #date {
        padding-top: 25px;
    }

    #points {
        width: 400px;
        border:none;
        background:none;
        border-bottom:1px dashed #888;
    }

    .normal {
        height: 23px;
    }

    .th-stagiaires {
        height: 40px;
    }

    #nom-prenom-stagiaire {
        font-size: 20px;
        text-align: center;
    }

    .th-formateur {
        height: 30px;
    }

    #formateur-signature {
        width: 225px;
    }

</style>

<body>
    <div id="entete">
        <div id="logo-interface">
            <img src="./images/Interface3-logo.png" alt="logo-interface3namur" class="logo" >
        </div>
        <div id="logo-pouvsub">
            <img src="{{ './images/logos/' . $pouvsub->logo }}" alt="logo-pouvoir-subsidiant" class="logo">
        </div>
    </div>
    <h3>Présences "{{ $formation->nom }}"</h3>
    <span class="infos" id="infos-formation">Du {{ date('d/m/Y', strtotime($formation->date_debut)) }} au {{ date('d/m/Y', strtotime($formation->date_fin)) }}</span><br>
    <div class="infos" id="date"><label for="date">Date : </label><input id="points" type="text"></div>
    <table id="table-stagiaires">
        <thead>
        <tr>
            <th id="nomprenom" class="th-stagiaires">NOM & PR&Eacute;NOM</th>
            <th class="auto th-stagiaires">SIGNATURE</th>
            <th class="temps th-stagiaires">P</th>
            <th class="temps th-stagiaires">AM/<br/>AI</th>
            <th class="temps th-stagiaires">RM/<br/>RI</th>
            <th id="arrivee" class="th-stagiaires">Heure<br/>arriv&eacute;e</th>
        </tr>
        </thead>
        <tbody>
        @foreach($stagiaires as $stagiaire)
            <tr>
                <td rowspan=2 id="nom-prenom-stagiaire">{{ mb_strtoupper($stagiaire->nom) . ' ' . mb_strtoupper($stagiaire->prenom) }}</td>
                <td class="normal"></td>
                <td class="normal"></td>
                <td class="normal"></td>
                <td class="normal"></td>
                <td class="normal"></td>
            </tr>
            <tr>
                <td class="normal"></td>
                <td class="normal"></td>
                <td class="normal"></td>
                <td class="normal"></td>
                <td class="normal"></td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <h4>Présence formateur·rice</h4>
    <table id="table-formateur">
        <thead>
        <tr>
            <th id="nomprenom" class="th-formateur">NOM & PR&Eacute;NOM</th>
            <th class="auto th-formateur">COURS</th>
            <th class="temps th-formateur">TPS</th>
            <th id="formateur-signature" class="th-formateur">SIGNATURE</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td class="normal"></td>
                <td class="normal"></td>
                <td class="normal">Matin</td>
                <td class="normal"></td>
            </tr>
            <tr>
                <td class="normal"></td>
                <td class="normal"></td>
                <td class="normal">Ap-m</td>
                <td class="normal"></td>
            </tr>
        </tbody>
    </table>
</body>
