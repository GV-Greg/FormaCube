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
        margin: 20px 0 20px 0;
        padding: 0;
        width: 100vw;
        font-family: 'blogger', sans-serif !important;
        font-weight: bold;
        color: #00768C;
        font-size: 35px;
    }

    #infos-formation {
        margin: 20px 0 20px 0;
        padding: 0;
        font-size: 25px;
        font-weight: bold;
        height: 50px;
    }

    #titre  {
        display: table;
        margin: 20px 0 -2px 0;
        padding: 0;
    }

    #titre th {
        background-color: #bababa;
        width: 100%;
        padding-top: 25px;
        height: 25px;
        font-family: 'blogger', sans-serif !important;
        font-weight: bold;
        font-size: 15pt;
    }

    table {
        margin: 0;
        padding: 0;
        width: 100%;
        border-collapse: collapse;
        border: 0.1em solid black;
    }

    td {
        border: 1px solid black;
        height: 50px;
        padding: 10px;
    }

    #table-absences {
        width: 100%;
    }

    #table-absences th {
        font-weight: bold;
        font-size: 12pt;
        line-height: 10pt;
    }

    #col-absence {
        width: 15%;
    }

    #col-nom-prenom {
        width: 25%;
    }

    #col-motif {
        width: 40%;
    }

    #col-tuteur {
        width: 20%;
    }

    .entete {
        height: 70px;
        border: 0.1em solid black;
    }

    .normal {
        height: 43px;
    }

    #footer {
        margin: 5px 0 -40px 0;
        font-size: 11pt;
        line-height: 9pt;
        text-align: center;
    }

    .bull {
        font-weight: bold;
        font-size: 16pt;
    }
</style>

<body>
    <div id="entete">
        <div id="logo-interface">
            <img src="./images/Interface3-logo.png" alt="logo-interface3namur" class="logo" >
        </div>
        @if($pouvsub->nom !== 'Interface' && $pouvsub->nom !== 'Interface3' && $pouvsub->nom !== 'Interface3Namur' && $pouvsub->nom !== 'Interface3.Namur' )
            <div id="logo-pouvsub">
                <img src="{{ './images/logos/' . $pouvsub->logo }}" alt="logo-pouvoir-subsidiant" class="logo">
            </div>
        @endif
    </div>
    <h3>Formation : "{{ $formation->nom }}"</h3>
    <span id="infos-formation">Du {{ \Carbon\Carbon::parse($formation->date_debut)->translatedFormat('l j F Y') }} au {{ \Carbon\Carbon::parse($formation->date_fin)->translatedFormat('l j F Y') }}</span><br>
    <table id="titre">
        <thead>
            <tr>
                <th>Absences Justifi&eacute;es</th>
            </tr>
        </thead>
    </table>
    <table id="table-absences">
        <thead>
            <tr>
                <th id="col-absence" class="entete">Date<br/>absence</th>
                <th id="col-nom-prenom" class="entete">NOM & Pr&eacute;nom</th>
                <th id="col-motif" class="entete">Motif</th>
                <th id="col-tuteur" class="entete">Validation<br/>tuteur</th>
            </tr>
        </thead>
        <tbody>
            @for($i=0; $i<18; $i++)
                <tr>
                    <td class="normal"></td>
                    <td class="normal"></td>
                    <td class="normal"></td>
                    <td class="normal"></td>
                </tr>
            @endfor
        </tbody>
    </table>
    <div id="footer">
        <strong>Interface3.Namur</strong> <span class="bull">&bull;</span> Avenue Sergent Vrithoff, 2 – 5000 Namur <span class="bull">&bull;</span> N° d’entreprise BE0870 417226<br/>
        Tél / Fax : 081/63 34 90 <span class="bull">&bull;</span> contact@interface3namur.be <span class="bull">&bull;</span> www.interface3namur.be
    </div>
</body>
