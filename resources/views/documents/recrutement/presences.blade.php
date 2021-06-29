<style type="text/css">
    body{
        font-family: Gadugi, sans-serif;
        width: 100vw;
        height: 100vh;
    }

    #container {

    }

    #entete {
        height: 150px;
    }

    .logo {
        width: 18%;
        z-index: 1;
    }

    #logo-interface {
        position: relative;
        float: left;
        z-index: 1;
        padding-top: 50px;
    }

    #logo-pouvsub {
        position: relative;
        float: right;
        z-index: 1;
    }

    h4 {
        position: absolute;
        width: 100vw;
        height: 50px;
        top: 50px;
        z-index: 2;
        text-align: center;
    }

    h5 {
        margin-top: -50px;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 50px;
    }

    table, th, td {
        border: 1px solid black;
    }

    th {
        height: 50px;
    }

    td {
        height: 50px;
        padding: 10px;
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
    <div id="container">
        <div id="entete">
            <div id="logo-interface">
                <img src="./images/Interface3-logo.png" alt="logo-interface3namur" class="logo" >
            </div>
            <div id="logo-pouvsub">
                <img src="{{ './images/logos/' . $pouvsub->logo }}" alt="logo-pouvoir-subsidiant" class="logo">
            </div>
        </div>
        <h4>Liste de présence pour la séance d'information</h4>
        Formation : <span><strong>{{ $formation->nom }}</strong></span><br>
        Date : <span><strong>{{ date('d/m/Y', strtotime($recrutement->date)) }}</strong></span>

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
    </div>
</body>
