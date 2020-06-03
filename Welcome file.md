---


---

<h1 id="projekt-inżynierski-anonimowe-ankietowanie">Projekt inżynierski (Anonimowe ankietowanie)</h1>
<p>Projekt i implementacja systemu webowego umożliwiającego głosowanie w sposób umożliwiający zachowanie anonimowości użytkowników.</p>
<p>System zrealizowany w formie aplikacji webowej uwzględnia możliwość oddania głosu w taki sposób, aby realizować następujące funkcje:<br>
• informacje przechowywane w bazie danych,<br>
• reprezentacja nie umożliwia powiązania użytkownika z konkretnymi danymi,<br>
• reprezentacja umożliwia sprawdzenie czy dana osoba przekazała dane,<br>
• reprezentacja umożliwia sprawdzenie przez użytkownika czy jego dane są zapisane w bazie,<br>
• system umożliwia weryfikację przez użytkownika czy jego odpowiedzi nie zostały zmodyfikowane.</p>
<p>Implementacja uwzględnia responsywny interfejs.</p>
<h2 id="użyte-technologie">Użyte technologie</h2>
<p><a href="https://github.com/mgorecky/projekt_inz/tree/master/api">API</a> aplikacji zostało napisane z wykorzystaniem frameworka PHP <a href="https://github.com/laravel/laravel">Laravel</a> w wersji 5.8.<br>
<a href="https://github.com/mgorecky/projekt_inz/tree/master/web">Frontend</a> aplikacji został napisany z wykorzystaniem frameworku JavaScript <a href="https://github.com/vuejs/vue">Vue.js</a>.<br>
Dane o użytkownikach oraz ankietach są zapisywane w relacyjnej bazie danych <a href="https://github.com/MariaDB/server">MariaDB</a> (działa również pod <a href="https://www.mysql.com/">MySQL</a>).</p>
<h1 id="dokumentacja">Dokumentacja</h1>
<p>Dokumentacja API dostępna jest pod linkiem: <a href="https://mgorecky.github.io/projekt_inz/">https://mgorecky.github.io/projekt_inz/</a></p>
<h2 id="token-odpowiedzi">Token odpowiedzi</h2>
<p>Podczas udzielania odpowiedzi na ankietę aplikacja przesyła do użytkownika token generowany na podstawie informacji o użytkowniku oraz udzielonych przez niego odpowiedzi. Token umożliwia późniejszą weryfikację czy dane wciąż znajdują się w bazie oraz czy nie zostały zmodyfikowane.</p>
<div class="mermaid"><svg xmlns="http://www.w3.org/2000/svg" id="mermaid-svg-Zg3mMXZgTMLdl0jb" width="100%" style="max-width: 282.71875px;" viewBox="0 0 282.71875 306"><g transform="translate(-12, -12)"><g class="output"><g class="clusters"></g><g class="edgePaths"><g class="edgePath" style="opacity: 1;"><path class="path" d="M153.359375,66L153.359375,104L153.359375,142" marker-end="url(#arrowhead197)" style="fill:none"></path><defs><marker id="arrowhead197" viewBox="0 0 10 10" refX="9" refY="5" markerUnits="strokeWidth" markerWidth="8" markerHeight="6" orient="auto"><path d="M 0 0 L 10 5 L 0 10 z" class="arrowheadPath" style="stroke-width: 1; stroke-dasharray: 1, 0;"></path></marker></defs></g><g class="edgePath" style="opacity: 1;"><path class="path" d="M153.359375,188L153.359375,226L153.359375,264" marker-end="url(#arrowhead198)" style="fill:none"></path><defs><marker id="arrowhead198" viewBox="0 0 10 10" refX="9" refY="5" markerUnits="strokeWidth" markerWidth="8" markerHeight="6" orient="auto"><path d="M 0 0 L 10 5 L 0 10 z" class="arrowheadPath" style="stroke-width: 1; stroke-dasharray: 1, 0;"></path></marker></defs></g></g><g class="edgeLabels"><g class="edgeLabel" transform="translate(153.359375,104)" style="opacity: 1;"><g transform="translate(-133.359375,-13)" class="label"><foreignObject width="266.71875" height="26"><div xmlns="http://www.w3.org/1999/xhtml" style="display: inline-block; white-space: nowrap;"><span class="edgeLabel">Przechowanie loginu i hasła w modelu</span></div></foreignObject></g></g><g class="edgeLabel" transform="translate(153.359375,226)" style="opacity: 1;"><g transform="translate(-102.875,-13)" class="label"><foreignObject width="205.75" height="26"><div xmlns="http://www.w3.org/1999/xhtml" style="display: inline-block; white-space: nowrap;"><span class="edgeLabel">Generowanie tokenu - bcrypt</span></div></foreignObject></g></g></g><g class="nodes"><g class="node" id="A" transform="translate(153.359375,43)" style="opacity: 1;"><rect rx="0" ry="0" x="-47.7265625" y="-23" width="95.453125" height="46"></rect><g class="label" transform="translate(0,0)"><g transform="translate(-37.7265625,-13)"><foreignObject width="75.453125" height="26"><div xmlns="http://www.w3.org/1999/xhtml" style="display: inline-block; white-space: nowrap;">Logowanie</div></foreignObject></g></g></g><g class="node" id="B" transform="translate(153.359375,165)" style="opacity: 1;"><rect rx="0" ry="0" x="-82.34375" y="-23" width="164.6875" height="46"></rect><g class="label" transform="translate(0,0)"><g transform="translate(-72.34375,-13)"><foreignObject width="144.6875" height="26"><div xmlns="http://www.w3.org/1999/xhtml" style="display: inline-block; white-space: nowrap;">Wypełnienie ankiety</div></foreignObject></g></g></g><g class="node" id="C" transform="translate(153.359375,287)" style="opacity: 1;"><rect rx="0" ry="0" x="-103.5625" y="-23" width="207.125" height="46"></rect><g class="label" transform="translate(0,0)"><g transform="translate(-93.5625,-13)"><foreignObject width="187.125" height="26"><div xmlns="http://www.w3.org/1999/xhtml" style="display: inline-block; white-space: nowrap;">Wysłanie tokenu e-mailem</div></foreignObject></g></g></g></g></g></g></svg></div>
<h2 id="build-setup">Build setup</h2>
<img src="http://endymion.pl/uploads/images/endy_9849a90aba2c12e3bd.png">
<pre class=" language-bash"><code class="prism  language-bash"><span class="token comment"># install dependencies</span>
<span class="token function">npm</span> <span class="token function">install</span>

<span class="token comment"># serve with hot reload at localhost:8080</span>
<span class="token function">npm</span> run dev

<span class="token comment"># build for production with minification</span>
<span class="token function">npm</span> run build

<span class="token comment"># build for production and view the bundle analyzer report</span>
<span class="token function">npm</span> run build --report
</code></pre>
<br>
<img src="https://laravel.com/assets/img/components/logo-laravel.svg">
<pre class=" language-bash"><code class="prism  language-bash"><span class="token comment"># install dependencies</span>
composer <span class="token function">install</span>

<span class="token comment"># copy env file</span>
<span class="token function">cp</span> .env.example .env

<span class="token comment"># generate app key</span>
php artisan key:generate

<span class="token comment"># migrate db</span>
php artisan migrate
php artisan db:seed

<span class="token comment"># serve at localhost:8000</span>
php artisan serve
</code></pre>

