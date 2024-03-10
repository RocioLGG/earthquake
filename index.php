<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Earthquakes Map</title>

    <link rel="shortcut icon" type="image/x-icon" href="https://educacionadistancia.juntadeandalucia.es/centros/jaen/pluginfile.php/144633/mod_page/content/9/docs/images/favicon.ico" />

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin=""/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" />
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #fdfcfb 0%, #e2d1c3 100%);
        }

        #mapid {
            width: 100vw;
            height: 70vh;
            margin: auto;
            display: block;
        }

        h1, h2 {
            text-align: center;
            margin-top: 20px;
        }

        p {
            text-align: center;
            margin-bottom: 20px;
        }

        #earthquake-table {
            margin: auto;
            width: 80%;
            border-collapse: collapse;
            border: 2px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }

        #earthquake-table th, #earthquake-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        #earthquake-table th {
            background-color: #f2f2f2;
        }

        #earthquake-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #earthquake-table tbody tr:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
<h1>Sismologia</h1>
<p>Consultar los datos de los terremotos en España.</p>
<div id="mapid"></div>
<table id="earthquake-table">
    <thead>
    <tr>
        <th>Ubicación</th>
        <th>Magnitud</th>
    </tr>
    </thead>
    <tbody id="earthquake-table-body">
    </tbody>
    <tfoot>
    <h2>Estadísticas</h2>
    <tr>
        <th>Ubicación</th>
        <th>Magnitud</th>
    </tr>
    </tfoot>
</table>
<script>
    var myIcon = L.icon({
        iconUrl: 'terremoto-sin-fondo.png',
        iconSize: [38, 38]
    });

    var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '© <a href="http://openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    });

    var map = L.map('mapid').setView([36.7305, -6.5356], 6).addLayer(osmLayer);

    var url = 'earthquaquer.php';

    fetch(url)
        .then(response => response.json())
        .then(data => {
            var earthquakeTableBody = document.getElementById('earthquake-table-body');
            data.forEach(item => {
                var marker = L.marker([item.lat, item.long], {icon: myIcon}).addTo(map);
                marker.bindPopup("<b>" + item.location + "</b><br>Magnitud: " + item.magnitude);
                var row = document.createElement('tr');
                row.innerHTML = "<td>" + item.location + "</td><td>" + item.magnitude + "</td>";
                earthquakeTableBody.appendChild(row);
            });

            var dataTable = $('#earthquake-table').DataTable({
                "initComplete": function () {
                    this.api()
                        .columns()
                        .every(function () {
                            let column = this;
                            let title = column.footer().textContent;

                            let input = document.createElement('input');
                            input.placeholder = title;
                            column.footer().replaceChildren(input);

                            input.addEventListener('keyup', () => {
                                if (column.search() !== this.value) {
                                    column.search(input.value).draw();
                                }
                            });
                        });
                }
            });
        })
        .catch(error => console.error('Error:', error));
</script>
</body>
</html>
