const CACHE_NAME = 'sisapp-cache-v1';

const urlsToCache = [

'/colegio-ascension/',
'/colegio-ascension/css/style.css',
'/colegio-ascension/js/app.js'

];

/* =========================================================
INSTALL
========================================================= */

self.addEventListener('install', event => {

event.waitUntil(

caches.open(CACHE_NAME)

.then(cache => {

return cache.addAll(urlsToCache);

})

);

});

/* =========================================================
FETCH
========================================================= */

self.addEventListener('fetch', event => {

event.respondWith(

caches.match(event.request)

.then(response => {

return response || fetch(event.request);

})

);

});