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
<img src="http://endymion.pl/uploads/files/endy_5b6565e0a1576830da.svg">
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

