// CodeMirror, copyright (c) by Marijn Haverbeke and others
// Distributed under an MIT license: http://codemirror.net/LICENSE

/*
Gherkin mode - http://www.cukes.info/
Report bugs/issues here: https://github.com/marijnh/CodeMirror/issues
*/

// Following Objs from Brackets implementation: https://github.com/tregusti/brackets-gherkin/blob/master/main.js
//var Quotes = {
//  SINGLE: 1,
//  DOUBLE: 2
//};

//var regex = {
//  keywords: /(Feature| {2}(Scenario|In order to|As|I)| {4}(Given|When|Then|And))/
//};

(function(mod) {
  if (typeof exports == "object" && typeof module == "object") // CommonJS
    mod(require("../../lib/codemirror"));
  else if (typeof define == "function" && define.amd) // AMD
    define(["../../lib/codemirror"], mod);
  else // Plain browser env
    mod(CodeMirror);
})(function(CodeMirror) {
"use strict";

CodeMirror.defineMode("gherkin", function () {
  return {
    startState: function () {
      return {
        lineNumber: 0,
        tableHeaderLine: false,
        allowFeature: true,
        allowBackground: false,
        allowScenario: false,
        allowSteps: false,
        allowPlaceholders: false,
        allowMultilineArgument: false,
        inMultilineString: false,
        inMultilineTable: false,
        inKeywordLine: false
      };
    },
    token: function (stream, state) {
      if (stream.sol()) {
        state.lineNumber++;
        state.inKeywordLine = false;
        if (state.inMultilineTable) {
            state.tableHeaderLine = false;
            if (!stream.match(/\s*\|/, false)) {
              state.allowMultilineArgument = false;
              state.inMultilineTable = false;
            }
        }
      }

      stream.eatSpace();

      if (state.allowMultilineArgument) {

        // STRING
        if (state.inMultilineString) {
          if (stream.match('"""')) {
            state.inMultilineString = false;
            state.allowMultilineArgument = false;
          } else {
            stream.match(/.*/);
          }
          return "string";
        }

        // TABLE
        if (state.inMultilineTable) {
          if (stream.match(/\|\s*/)) {
            return "bracket";
          } else {
            stream.match(/[^\|]*/);
            return state.tableHeaderLine ? "header" : "string";
          }
        }

        // DETECT START
        if (stream.match('"""')) {
          // String
          state.inMultilineString = true;
          return "string";
        } else if (stream.match("|")) {
          // Table
          state.inMultilineTable = true;
          state.tableHeaderLine = true;
          return "bracket";
        }

      }

      // LINE COMMENT
      if (stream.match(/#.*/)) {
        return "comment";

      // TAG
      } else if (!state.inKeywordLine && stream.match(/@\S+/)) {
        return "tag";

      // FEATURE
      } else if (!state.inKeywordLine && state.allowFeature && stream.match(/(      |      |               |      |                        |                              |                                                            |                  |               |                        |                         |                  |                   |            |          |          |                    |              |                            |                    |                            |                |              |                  |                  |                    |                    |W  a  ciwo    |Vlastnos  |Trajto|T  nh n  ng|Savyb  |Pretty much|Po  iadavka|Po  adavek|Potrzeba biznesowa|  zellik|Osobina|Ominaisuus|Omadus|OH HAI|Mogu  nost|Mogucnost|Jellemz  |Hw  t|Hwaet|Funzionalit  |Funktionalit  it|Funktionalit  t|Funkcja|Funkcionalnost|Funkcionalit  te|Funkcia|Fungsi|Functionaliteit|Func  ionalitate|Func  ionalitate|Functionalitate|Funcionalitat|Funcionalidade|Fonctionnalit  |Fitur|F    a|Feature|Eiginleiki|Egenskap|Egenskab|Caracter  stica|Caracteristica|Business Need|Aspekt|Arwedd|Ahoy matey!|Ability):/)) {
        state.allowScenario = true;
        state.allowBackground = true;
        state.allowPlaceholders = false;
        state.allowSteps = false;
        state.allowMultilineArgument = false;
        state.inKeywordLine = true;
        return "keyword";

      // BACKGROUND
      } else if (!state.inKeywordLine && state.allowBackground && stream.match(/(      |      |                  |                        |                     |                  |                           |          |              |      |          |                      |                      |                |                    |            |                |          |                |Za  o  enia|Yo\-ho\-ho|Tausta|Taust|Situ  cija|Rerefons|Pozadina|Pozadie|Pozad  |Osnova|Latar Belakang|Kontext|Konteksts|Kontekstas|Kontekst|H  tt  r|Hannergrond|Grundlage|Ge  mi  |Fundo|Fono|First off|Dis is what went down|Dasar|Contexto|Contexte|Context|Contesto|Cen  rio de Fundo|Cenario de Fundo|Cefndir|B   i c   nh|Bakgrunnur|Bakgrunn|Bakgrund|Baggrund|Background|B4|Antecedents|Antecedentes|  r|Aer|Achtergrond):/)) {
        state.allowPlaceholders = false;
        state.allowSteps = true;
        state.allowBackground = false;
        state.allowMultilineArgument = false;
        state.inKeywordLine = true;
        return "keyword";

      // SCENARIO OUTLINE
      } else if (!state.inKeywordLine && state.allowScenario && stream.match(/(            |            |            |            |            |                              |                        |                              |                   |                                       |                                                               |                  |            |                                      |                               |                                              |                       |                       |                     |                                       |                                       |                                   |                                   |                                     |          |                                |              |                                   |Wharrimean is|Template Situai|Template Senario|Template Keadaan|Tapausaihio|Szenariogrundriss|Szablon scenariusza|Swa hw  r swa|Swa hwaer swa|Struktura scenarija|Structur   scenariu|Structura scenariu|Skica|Skenario konsep|Shiver me timbers|Senaryo tasla    |Schema dello scenario|Scenariomall|Scenariomal|Scenario Template|Scenario Outline|Scenario Amlinellol|Scen  rijs p  c parauga|Scenarijaus   ablonas|Reckon it's like|Raamstsenaarium|Plang vum Szenario|Plan du Sc  nario|Plan du sc  nario|Osnova sc  n    e|Osnova Scen  ra|N    rt Scen  ru|N    rt Sc  n    e|N    rt Scen  ra|MISHUN SRSLY|Menggariskan Senario|L  sing D  ma|L  sing Atbur  ar  sar|Konturo de la scenaro|Koncept|Khung t  nh hu   ng|Khung k   ch b   n|Forgat  k  nyv v  zlat|Esquema do Cen  rio|Esquema do Cenario|Esquema del escenario|Esquema de l'escenari|Esbozo do escenario|Delinea    o do Cen  rio|Delineacao do Cenario|All y'all|Abstrakt Scenario|Abstract Scenario):/)) {
        state.allowPlaceholders = true;
        state.allowSteps = true;
        state.allowMultilineArgument = false;
        state.inKeywordLine = true;
        return "keyword";

      // EXAMPLES
      } else if (state.allowScenario && stream.match(/(      |   |            |   |                                             |                                          |                              |                        |                        |                  |               |          |              |                |                  |              |              |                |                |                |              |                        |You'll wanna|Voorbeelden|Variantai|Tapaukset|Se   e|Se the|Se   e|Scenarios|Scenariji|Scenarijai|Przyk  ady|Primjeri|Primeri|P    klady|Pr  klady|Piem  ri|P  ld  k|Pavyzd  iai|Paraugs|  rnekler|Juhtumid|Exemplos|Exemples|Exemple|Exempel|EXAMPLZ|Examples|Esempi|Enghreifftiau|Ekzemploj|Eksempler|Ejemplos|D    li   u|Dead men tell no tales|D  mi|Contoh|Cen� rios|Cenarios|Beispiller|Beispiele|Atbur  ar  sir):/)) {
        state.allowPlaceholders = false;
        state.allowSteps = true;
        state.allowBackground = false;
        state.allowMultilineArgument = true;
        return "keyword";

      // SCENARIO
      } else if (!state.inKeywordLine && state.allowScenario && stream.match(/(      |      |      |      |            |            |                           |                           |                           |               |                        |              |            |          |                |                |                |            |              |T  nh hu   ng|The thing of it is|Tapaus|Szenario|Swa|Stsenaarium|Skenario|Situai|Senaryo|Senario|Scenaro|Scenariusz|Scenariu|Sc  nario|Scenario|Scenarijus|Scen  rijs|Scenarij|Scenarie|Sc  n    |Scen  r|Primer|MISHUN|K   ch b   n|Keadaan|Heave to|Forgat  k  nyv|Escenario|Escenari|Cen  rio|Cenario|Awww, look mate|Atbur  ar  s):/)) {
        state.allowPlaceholders = false;
        state.allowSteps = true;
        state.allowBackground = false;
        state.allowMultilineArgument = false;
        state.inKeywordLine = true;
        return "keyword";

      // STEPS
      } else if (!state.inKeywordLine && state.allowSteps && stream.match(/(      |      |      |   |   |      |      |      |      |      |      |      |      |      |      |      |      |         |         |         |      |         |      |      |      |      |   |         |         |          |                |          |                      |                         |                                  |                |                   |             |             |                |                                  |             |                                      |                      |       |       |             |                       |             |          |          |                   |       |       |          |          |       |                |                   |          |       |          |   |             |       |       |           |     |         |            |       |         |           |         |       |             |       |     |       |         |       |         |         |           |     |           |     |           |                    ,      |                     |         |     |           |                 |           |           |         |           |             |         |       |                 |   |   |             |             |             |         |                 |         |             |     |     |           |         |           |         |         |       |       |         |              |   |         |         |       |                   |         |  urh |  egar |  a   e |     |  a |Zatati |Zak  adaj  c |Zadato |Zadate |Zadano |Zadani |Zadan |Za p  edpokladu |Za predpokladu |Youse know when youse got |Youse know like when |Yna |Yeah nah |Y'know |Y |Wun |Wtedy |When y'all |When |Wenn |WEN |wann |Ve |V   |Und |Un |ugeholl |Too right |Thurh |Th   |Then y'all |Then |Tha the |Tha |Tetapi |Tapi |Tak |Tada |Tad |Stel |Soit |Siis |  i |  i |Si |Sed |Se |S   |Quando |Quand |Quan |Pryd |Potom |Pokud |Pokia   |Per   |Pero |Pak |Oraz |Onda |Ond |Oletetaan |Og |Och |O zaman |Niin |Nh  ng |N  r |N  r |Mutta |Men |Mas |Maka |Majd |Maj  c |Mais |Maar |m   |Ma |Lorsque |Lorsqu'|Logo |Let go and haul |Kun |Kuid |Kui |Kiedy |Khi |Ketika |Kemudian |Ke   |Kdy   |Kaj |Kai |Kada |Kad |Je  eli |Je  li |Ja |It's just unbelievable |Ir |I CAN HAZ |I |Ha |Givun |Givet |Given y'all |Given |Gitt |Gegeven |Gegeben seien |Gegeben sei |Gdy |Gangway! |Fakat |  tant donn  s |Etant donn  s |  tant donn  es |Etant donn  es |  tant donn  e |Etant donn  e |  tant donn   |Etant donn   |Et |  s |Entonces |Ent  n |Ent  o |Entao |En |E  er ki |Ef |Eeldades |E |  urh |Duota |Dun |Donita  o |Donat |Donada |Do |Diyelim ki |Diberi |Dengan |Den youse gotta |DEN |De |Dato |Da  i fiind |Da  i fiind |Dati fiind |Dati |Date fiind |Date |Data |Dat fiind |Dar |Dann |dann |Dan |Dados |Dado |Dadas |Dada |  a   e |  a |Cuando |Cho |Cando |C  nd |Cand |Cal |But y'all |But at the end of the day I reckon |BUT |But |Buh |Blimey! |Bi   t |Bet |Bagi |Aye |awer |Avast! |Atunci |Atesa |At  s |Apabila |Anrhegedig a |Angenommen |And y'all |And |AN |An |an |Amikor |Amennyiben |Ama |Als |Alors |Allora |Ali |Aleshores |Ale |Akkor |Ak |Adott |Ac |Aber |A z  rove   |A tie   |A taktie   |A tak   |A |a |7 |\* )/)) {
        state.inStep = true;
        state.allowPlaceholders = true;
        state.allowMultilineArgument = true;
        state.inKeywordLine = true;
        return "keyword";

      // INLINE STRING
      } else if (stream.match(/"[^"]*"?/)) {
        return "string";

      // PLACEHOLDER
      } else if (state.allowPlaceholders && stream.match(/<[^>]*>?/)) {
        return "variable";

      // Fall through
      } else {
        stream.next();
        stream.eatWhile(/[^@"<#]/);
        return null;
      }
    }
  };
});

CodeMirror.defineMIME("text/x-feature", "gherkin");

});
