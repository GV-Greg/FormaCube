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
        height: 100px;
    }

    #logo {
        width: 30%;
        position: relative;
        float: left;
        margin-top: -25px;
    }

    h3 {
        width: 100vw;
        height: 50px;
        margin: 0;
        padding: 0;
        font-family: 'blogger', sans-serif !important;
        font-weight: bold;
        color: #00768C;
        font-size: 35px;
    }

    .container-infos {
        margin: 0 0 -20px 0;
        padding: 0;
    }

    .infos {
        font-size: 25px;
        font-weight: bold;
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
        height: 45px;
    }

    td {
        border: 1px solid black;
        height: 45px;
        padding: 0.37em;
    }

    #nom {
        width: 250px;
    }

    #prenom {
        width: 175px;
    }

    .auto {
        width: auto;
    }
</style>

<body>
    <div id="entete">
        <div id="logo">
            <img src="./storage/images/logos/Logo_FormaCube-Noir-500x100.png" alt="logo-FormaCube">
        </div>
    </div>
    <h3>Liste de présence pour la séance d'information</h3>
    <div class="container-infos">Formation : <span class="infos">{{ $formation->nom }}</span></div><br>
    <div class="container-infos">Date : <span class="infos">{{ date('d/m/Y', strtotime($recrutement->date)) }}</span></div><br>
    <table>
        <thead>
            <tr>
                <th id="nom">NOM</th>
                <th id="prenom">PR&Eacute;NOM</th>
                <th class="auto">O&Uacute; EU L'INFO ?</th>
                <th class="auto">SIGNATURE</th>
            </tr>
        </thead>
        <tbody>
            @foreach($candidats as $candidat)
                <tr>
                    <td>{{ mb_strtoupper($candidat->nom) }}</td>
                    <td>{{ ucfirst($candidat->prenom) }}</td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
