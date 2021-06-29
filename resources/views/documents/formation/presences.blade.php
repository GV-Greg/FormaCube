<style type="text/css">
    body{
        font-family: Gadugi, sans-serif;
        width: 100vw;
        height: 100vh;
    }

    #entete {
        height: 150px;
    }

    .logo {
        width: 18%;
    }

    #logo-interface {
        position: relative;
        float: left;
        top: 20px;
    }

    #logo-pouvsub {
        position: relative;
        float: right;
    }

    #infos-formation {
        margin-top: -100px;
    }

    h3 {
        width: 100vw;
        height: 50px;
        margin-top: -50px;
        margin-bottom: -10px;
    }

    h4 {
        margin-top: 5px;
        margin-bottom: 5px;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    #table-stagiaires {
        margin-top: 15px;
    }

    #table-formateur {
        margin-top: -5px;
    }

    table, th, td {
        border: 1px solid black;
    }

    td {
        height: 50px;
        padding: 10px;
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
<div id="container">
    <div id="entete">
        <div id="logo-interface">
            <img src="./images/Interface3-logo.png" alt="logo-interface3namur" class="logo" >
        </div>
        <div id="logo-pouvsub">
            <img src="{{ './images/logos/' . $pouvsub->logo }}" alt="logo-pouvoir-subsidiant" class="logo">
        </div>
    </div>
    <h3>Présences "{{ $formation->nom }}"</h3>
    <span class="infos" id="infos-formation">Du {{ $formation->date_debut }} au {{ $formation->date_fin }} - Horaire : 8h45-12h15 & 13h-16h30</span><br>
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
</div>
</body>
