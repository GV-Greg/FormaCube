<style>
    body{
        font-family: 'gadugi', sans-serif !important;
        font-weight: normal;
        width: 100vw;
        height: 100vh;
        margin: 0;
        padding: 0;
    }

    #entete {
        height: 125px;
        margin-top: 100px;
    }

    .logo {
        width: 25%;
    }

    #logo-if3n {
        width: 30%;
    }

    #logo-interface {
        position: relative;
        float: left;
        margin-top: -25px;
        margin-left: 75px;
    }

    #logo-pouvsub {
        position: relative;
        float: right;
        margin-top: -75px;
        margin-right: 30px;
    }

    #container-titre {
        margin: 25px 75px 0 75px;
        padding-top: 10px;
        padding-bottom: -25px;
        line-height: 15px;
        border: 1px solid black;
        text-align: center;
    }

    h1 {
        font-size: 32px;
        font-weight: bold;
        font-family: 'blogger', sans-serif !important;
    }

    h2 {
        font-size: 25px;
        font-weight: normal;
        font-family: 'gadugi', sans-serif !important;
    }

    .bold {
        font-weight: bold;
    }

    .italic {
        font-style: italic;
    }

    .body-text {
        margin: 0 75px 50px 75px;
        font-size: 20px;
        line-height: 23px;
        list-style: inside;
    }

    .sous-titre, .sous-titre-page3 {
        font-size: 25px;
        line-height: 20px;
        font-weight: bolder;
        font-family: 'blogger', sans-serif !important;
    }

    .sous-titre {
        margin: 60px 0 10px 0;
    }

    .sous-titre-page3 {
        margin: 45px 0 10px 0;
    }

    #p1-after-nom {
        margin-left: 155px;
        margin-right: 30px;
    }

    #p1-after-prenom {
        margin-left: 47px;
    }

    #p1-after-ville {
        margin-left: 100px;
        margin-right: 30px;
    }

    #p1-after-tel {
        margin-left: 30px;
    }

    #p1-after-email {
        margin-left: 144px;
    }

    #p1-after-naissance {
        margin-left: 40px;
    }

    ul {
        list-style: none;
    }

    ul li {
        line-height: 13px;
        margin-bottom: 15px;
    }

    ul li::before {
        content: '•';
        font-size: 25px;
        padding-right: 20px;
    }

    ul .sous-list::before {
        content: '\00BB';
        padding-right: 15px;
    }

    ul .sous-list:last-child {
        margin-bottom: 0;
    }

    #p1-after-li1 {
        margin-left: 30px;
    }

    #p1-after-li2 {
        margin-left: 72px;
    }

    #p1-after-li3 {
        margin-left: 281px;
    }

    #p1-after-li4 {
        margin-left: 260px;
    }

    #p1-after-li5 {
        margin-left: 299px;
    }

    #p1-after-li6 {
        margin-left: 370px;
    }

    #p1-after-li7 {
        margin-left: 139px;
    }

    table {
        width: 100%;
        margin: 20px;
        border: 1px solid black;
        border-collapse: collapse;
        table-layout: fixed;

    }

    table thead tr td {
        text-align: center;
        font-weight: bold;
    }

    .col-first {
        width: 20%;
    }

    td {
        line-height: 15px;
        border: 1px solid black;
        padding: 10px 10px;
    }

    .td-first {
        line-height: 18px;
    }

    .td-vide {
        height: 30px;
    }

    .list-element {
         margin-left: 40px;
    }

    p {
        margin: 0;
        padding: 0;
        line-height: 25px;
    }

    #page2 {
        margin-top: 125px
    }

    #page3 {
        margin-top: 50px
    }

    .checkbox {
        width: 15px;
        height: 15px;
        top: 10px;
        position: absolute;
        border: 2px solid black;
    }

    .checkbox-content {
        position: relative;
        margin-left: 30px;
    }

    ol {
        font-size: 19px;
        line-height: 17px;
        margin: 0 0 0 25px;
        padding: 0;
    }

    ol li {
        text-align: justify;
        list-style-position: outside;
        margin: 0 0 10px 0;
        padding: 2px 0 0 0;
    }

    #choix-newsletter {
        margin-top: 5px;
        margin-left: 75px;
    }

    #consentement-newsletter {
        text-align: justify;
        font-size: 19px;
        line-height: 17px;
    }

    #date {
        margin-top: 15px;
    }

    #recopie {
        margin-left: 75px;
    }

    #signature {
        margin-left: 370px;
    }

    #signature div {
        margin-top: 20px;
    }

    #signature div:last-child {
        margin-top: 35px;
    }

    .footer {
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

    .bullet {
        font-size: 25px;
        font-weight: bold;
    }
</style>

<body>
    <div id="entete">
        <div id="logo-interface">
            <img id="logo-if3n" src="./images/Interface3-logo.png" alt="logo-interface3namur">
        </div>
        <div id="logo-pouvsub">
            <img src="{{ './images/logos/' . $pouvsub->logo }}" alt="logo-pouvoir-subsidiant" class="logo">
        </div>
    </div>
    <div id="container-titre">
        <h1>Formation &laquo;{{ $formation->nom }}&raquo; - {{ $formation->session }}</h1>
        <h2>S&eacute;ance d'information du {{ $recrutement->date->isoformat('d MMMM Y') }}</h2>
    </div>
    <div class="body-text">
        <div class="sous-titre">Identit&eacute;</div>
        <div>Nom : <span id="p1-after-nom">. . . . . . . . . . . . . . . . . . . . . . . . . . . . .</span> Pr&eacute;nom : <span id="p1-after-prenom">. . . . . . . . . . . . . . . . . . . . . . . . . . . . . </span></div>
        <div>CP/Localit&eacute; : <span id="p1-after-ville">. . . . . . . . . . . . . . . . . . . . . . . . . . . . .</span> GSM / T&eacute;l : <span id="p1-after-tel">. . . . . . . . . . . . . . . . . . . . . . . . . . . . .</span></div>
        <div>E-mail : <span id="p1-after-email">. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .</span></div>
        <div>Date de naissance : <span id="p1-after-naissance">. . . . . . . . . . . . . . . . . . . . . . . . . . . . .</span></div>
        <div class="sous-titre">Statut socio-&eacute;nomique</div>
        <ul>
            <li>&Ecirc;tes-vous inscrit&middot;e comme demandeur&middot;euse d'emploi ?<span id="p1-after-li1">OUI – NON</span></li>
            <li>&Ecirc;tes-vous en phase de cr&eacute;ation d'entreprenariat ?<span id="p1-after-li2">OUI – NON</span></li>
            <li>&Ecirc;tes-vous ind&eacute;pendante ?<span id="p1-after-li3">OUI – NON</span></li>
            <li>&Ecirc;tes-vous sous la mutuelle ?<span id="p1-after-li4">OUI – NON</span></li>
            <li>Avez-vous un n° AVIQ ?<span id="p1-after-li5">OUI – NON</span></li>
            <li>Percevez-vous des allocations de :</li>
            <ul>
                <li class="sous-list">Ch&ocirc;mage ?<span id="p1-after-li6">OUI – NON</span></li>
                <li class="sous-list">Revenu d'Int&eacute;gration Sociale (CPAS) ?<span id="p1-after-li7">OUI – NON</span></li>
            </ul>
        </ul>
        <div class="sous-titre">&Eacute;tudes et formations</div>
        <div class="bold ">Indiquez le dernier dipl&ocirc;me obtenu.</div>
        <div class="italic">
            Si vous avez un CV, vous pouvez joindre une copie à la fiche signal&eacute;que et ne pas compl&eacute;ter cette partie.
            Merci d'indiquer &laquo; cfr CV &raquo; si c'est le cas.
        </div>
        <table>
            <thead>
                <tr>
                    <td>Niveau</td>
                    <td>École</td>
                    <td>Orientation</td>
                    <td>Lieu</td>
                    <td>R&eacute;ussi ou<br />&eacute;chou&eacute;</td>
                    <td>Ann&eacute;e ou<br />dipl&ocirc;me</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>CEB</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Ens. Sec. Inf.</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Ens. Sec. Sup.</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Graduat/<br />Bachelier</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Universitaire</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <div class="checkbox-container"><div class="checkbox"></div><div class="checkbox-content">Pas de dipl&ocirc;me</div></div>
        <div class="checkbox-container"><div class="checkbox"></div><div class="checkbox-content">Dipl&ocirc;me &eacute;tranger (pas d'&eacute;quivalence en Belgique)</div></div>
        <div class="checkbox-container"><div class="checkbox"></div><div class="checkbox-content">Dipl&ocirc;me &eacute;tranger &eacute;quivalence en cours ou &eacute;quivalence à (précisez) : . . . . . . . . . . . . . . .</div></div>
        <div class="checkbox-container"><div class="checkbox"></div><div class="checkbox-content">Autres : . . . . . . . . . . . . . . .</div></div>
    </div>
    <div class="footer">
        <b>Interface3.Namur</b> <span class="bullet">&#8226;</span> Avenue Sergent Vrithoff, 2 – 5000 Namur <span class="bullet">&#8226;</span> N° d'entreprise/TVA BE0870 417226 <br />
        Tél/Fax : 081/63.34.90 <span class="bullet">&#8226;</span> contact@interface3namur.be <span class="bullet">&#8226;</span> www.interface3namur.be
    </div>
    {{-- Page 2 --}}
    <div class="body-text" id="page2">
        <div class="bold">Autres formations</div>
        <div class="italic">
            Si vous avez un CV, vous pouvez joindre une copie à la fiche signal&eacute;que et ne pas compl&eacute;ter cette partie.
            Merci d'indiquer &laquo; cfr CV &raquo; si c'est le cas.
        </div>
        <table>
            <thead>
                <tr>
                    <td>Formation</td>
                    <td>Lieu</td>
                    <td>Date</td>
                </tr>
            </thead>
            <tbody>
                @for($i=1; $i<4; $i++)
                    <tr>
                        <td class="td-vide"></td>
                        <td class="td-vide"></td>
                        <td class="td-vide"></td>
                    </tr>
                @endfor
            </tbody>
        </table>
        <div class="sous-titre">Exp&eacute;rience professionnelle :</div>
        <div><span class="bold">Le plus pr&eacute;cis et le plus complet possible</span> en commen&ccedil;ant par votre dernier emploi (ex. : 07/2013 – 02/2015)</div>
        <div class="italic">
            Si vous avez un CV, vous pouvez joindre une copie à la fiche signal&eacute;que et ne pas compl&eacute;ter cette partie.
            Merci d'indiquer &laquo; cfr CV &raquo; si c'est le cas.
        </div>
        <table>
            <thead>
            <tr>
                <td class="col-first">Date</td>
                <td>Fonction</td>
                <td>Entreprise</td>
                <td>Lieu</td>
                <td>Type de contrat</td>
                <td>Motif du d&eacute;part</td>
            </tr>
            </thead>
            <tbody>
            @for($i=1; $i<4; $i++)
                <tr>
                    <td class="td-first">De &nbsp;. . . . . / . . . . . <br />&agrave; &nbsp; &nbsp; . . . . . / . . . . . </td>
                    <td class="td-vide"></td>
                    <td class="td-vide"></td>
                    <td class="td-vide"></td>
                    <td class="td-vide"></td>
                    <td class="td-vide"></td>
                </tr>
            @endfor
            </tbody>
        </table>
        <div class="sous-titre">Motivation :</div>
        <div>R&eacute;sumez en quelques lignes ce qui vous a motiv&eacute; &agrave; vous inscrire &agrave; la formation :</div>
        <p>
            . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .
            . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .
            . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .
        </p>
        <div>Ce que vous attendez de cette formation : Quels sont vos objectifs ?</div>
        <div class="list-element">- professionnels ou de formation :</div>
        <p>
            . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .
            . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .
            . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .
        </p>
        <div class="list-element">- personnels :</div>
        <p>
            . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .
            . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .
            . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .
        </p>
    </div>
    {{-- Page 2 --}}
    <div class="body-text" id="page3">
        <div>Quelles sont les derni&egrave;res actions de recherche d'emploi et de formation men&eacute;es ?</div>
        <p>
            . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .
            . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .
            . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .
            . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .
        </p>
        <div>Quels sont les plus grands obstacles &agrave; votre recherche d'emploi ou formation ?</div>
        <p>
            . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .
            . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .
            . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .
            . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .
        </p>
        <div class="sous-titre-page3">Renseignement pour Interface3.Namur :</div>
        <div>Comment avez-vous &eacute;t&eacute; de l'existence de cette formation ?</div>
        <div class="checkbox-container"><div class="checkbox"></div><div class="checkbox-content">Annonce</div></div>
        <div class="checkbox-container"><div class="checkbox"></div><div class="checkbox-content">Bouche &agrave; oreille</div></div>
        <div class="checkbox-container"><div class="checkbox"></div><div class="checkbox-content">Partenaire (Forem, Cefor, CPAS...). Précisez : . . . . . . . . . . . . . . . . . . . . . . . .</div></div>
        <div class="checkbox-container"><div class="checkbox"></div><div class="checkbox-content">Autres : . . . . . . . . . . . . . . . . . . . . . . . .</div></div>
        <div class="sous-titre-page3">Traitement des données :</div>
        <ol>
            <li>
                En vertu du R&egrave;glement G&eacute;n&eacute;ral pour la Protection des Donn&eacute;es (RGPD), nous vous informons que les données personnelles que vous nous avez communiqu&eacute; via ce question seront utilis&eacute;es
                dans le cadre de la proc&eacute;dure de s&eacute;lection pour la formation &laquo; {{ $formation->nom }} &raquo; (du {{ $formation->date_debut->isoformat('d MMMM Y') }} au {{ $formation->date_fin->isoformat('d MMMM Y') }})
                soutenues par le {{ $pouvsub->nom }}. Si vous &ecirc;tes s&eacute;lectionn&eacute;&middot;e, vos donn&eacute;es d'identification (nom, pr&eacute;nom, adresse postale, adressse mail, t&eacute;l&eacute;phone) seront
                conserv&eacute;es par Interface3.Namur pendant 10 ans, dur&eacute;e pendant laquelle l'ASBL doit garder les pi&egrave;ces justificatives sur la formation donn&eacute;e pour r&eacute;pondre aux demandes du pouvoir subsidiant.
                Si vous n'avez pas &eacute;t&eacute; s&eacute;lectionn&eacute;&middot;e, ces donn&eacute;es seront conserv&eacute;es par Interface3.Namur pendant {{ $formation->duree_rgpd }} pour r&eacute;pondre aux demandes du pouvoir subsidiant.
            </li>
            <li>
                Interface3.Namur publie r&eacute;fuli&egrave;rement une lettre d'information (newsletter) annon&ccedil;ant les nouvelles formations et autres &eacute;v&egrave;nements programm&eacute; par l'asbl.
            </li>
        </ol>
        <div class="bold">Je souhaite recevoir la newsletter d'Interface3.Namur (cochez la mention qui vous convient) :</div>
        <div id="choix-newsletter">
            <div class="checkbox-container"><div class="checkbox"></div><div class="checkbox-content">OUI &nbsp; email : . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .</div></div>
            <div class="checkbox-container"><div class="checkbox"></div><div class="checkbox-content">NON</div></div>
        </div>
        <div id="consentement-newsletter">
            En vous abonnant &agrave; la newsletter d'Interface3.Namur, vous donnez votre consentement pour la collecte et le traitement de vos donn&eacute;es personnelles (nom, pr&eacute;nom, adresse mail) par l'asbl. Ces donn&eacute;es ne seront pas
            communiqu&eacute;es &agrave; des tiers, except&eacute; au prestataire utilis&eacute; pour l'envoi des newsletters dans le respect du RGPD. Elles ne seront conserv&eacute;es que jusqu'&agrave; une &eacute;ventuelle demande de votre part de les supprimer
            en vertu de votre droit &agrave; l'oubli garanti par le R&egrave;glement europ&eacute;en pour la protection des donn&eacute;es (RGPD). Afin d'exercer celui-ci, vous pouvez &agrave; tout moment, manifester votre volont&eacute; en envoyant
            une demande au Responsable du traitement d'Interface3.Namur via le formulaire de contact du site d'Interface.Namur (www.interface3namur.be).
        </div>
        <div id="date">
            <span>Date . . . . . . . . . . . . . . . . . . . . . . . . .</span>
            <span id="recopie">Recopiez la mention <span class="bold">&laquo; Certifi&eacute; sinc&egrave;re et exact &raquo; :</span></span>
        </div>
        <div id="signature">
            <div>. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .</div>
            <div>Nom et pr&eacute;nom : . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .</div>
            <div>Signature : . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .</div>
        </div>
    </div>
</body>
