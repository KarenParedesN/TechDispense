self.addEventListener("install", (e) => {
  e.waitUntil(
    caches.open("mi-cache").then((cache) => {
      return cache.addAll([
        "/",
        "/index.html",
        "/img/logo.jpg",
        "/css/index.css"
      ]);
    })
  );
});

self.addEventListener("fetch", (e) => {
  e.respondWith(
    caches.match(e.request).then((respuesta) => {
      return respuesta || fetch(e.request);
    })
  );
});
