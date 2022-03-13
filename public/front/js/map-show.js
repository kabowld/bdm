$(function() {
    let map = document.querySelector('#map-show')
    if (map === null) {
        return
    }
    let icon = L.icon({
        iconUrl: '/front/images/marker-icon.png',
    })
    let center = [map.dataset.lat, map.dataset.lng]
    map = L.map('map-show').setView(center, 13)
    L.tileLayer(`https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png`, {
        maxZoom: 18,
        minZoom: 12,
        attribution: '© <a href="https://www.mapbox.com/feedback/">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map)
    L.marker(center, {icon: icon}).addTo(map)
})