function createExcelExportButton(config) {
    // El botón personalizado para exportar Excel
    $('#downloadExcelBtn').click(function () {
        tableMain.button(0).trigger();
    });

    return {
        text: config.text || 'Descargar Excel',
        action: function (e, dt, button, buttonConfig) {
            // Obtén la URL dinámica de la tabla
            var currentParams = $.extend({}, dt.ajax.params());
            currentParams.pageLength = -1;
            currentParams.length = -1;

            $.ajax({
                url: dt.ajax.url(), // Usamos la URL configurada de DataTables
                method: 'POST',
                data: currentParams,
                success: function (data) {
                    var headers = [];
                    dt.columns().header().each(function (header) {
                        headers.push($(header).text());
                    });

                    var columnsToOmit = config.columnsToOmit || [];
                    var columnsNoCustomRender = config.columnsNoCustomRender || [];
                    var columnsAlternateData = config.columnsAlternateData || {}; // Nuevo parámetro

                    var columnDefs = dt.settings()[0].aoColumns;

                    var exportData = data.aaData.map(function (rowData) {
                        return columnDefs.map(function (col, index) {
                            if (!columnsToOmit.includes(index)) {
                                // Verifica si se debe usar un campo alternativo para esta columna
                                var field = columnsAlternateData[index] || col.mData;

                                var cellData = rowData[field];

                                // Verificamos si el valor es null o undefined y lo reemplazamos por ""
                                if (cellData === null || cellData === undefined) {
                                    cellData = "";
                                }

                                // Si no está en columnsNoCustomRender, aplicamos el renderizado personalizado
                                if (!columnsNoCustomRender.includes(index) && col.mRender && typeof col.mRender === 'function') {
                                    return col.mRender(cellData, 'export', rowData, {
                                        row: null,
                                        col: index
                                    });
                                }

                                return cellData;
                            }
                        }).filter(function (value) {
                            return value !== undefined;
                        });
                    });

                    var filteredHeaders = headers.filter(function (header, index) {
                        return !columnsToOmit.includes(index);
                    });

                    var fullExportData = [filteredHeaders].concat(exportData);

                    var ws = {};
                    var range = { s: { c: 0, r: 0 }, e: { c: filteredHeaders.length - 1, r: fullExportData.length - 1 } };

                    for (var R = 0; R < fullExportData.length; R++) {
                        for (var C = 0; C < fullExportData[R].length; C++) {
                            var cellAddress = XLSX.utils.encode_cell({ r: R, c: C });
                            ws[cellAddress] = {
                                v: fullExportData[R][C],
                                t: 's',
                                s: R === 0 ? { 
                                    fill: { fgColor: { rgb: "000000" } }, 
                                    font: { color: { rgb: "FFFFFF" } }, 
                                    alignment: { horizontal: "center" } 
                                } : { alignment: { horizontal: "left" } }
                            };
                        }
                    }

                    ws['!ref'] = XLSX.utils.encode_range(range);

                    // Crea el libro de trabajo
                    var wb = { SheetNames: ['Datos'], Sheets: { Datos: ws } };

                    // Genera el archivo Excel en formato binario
                    var wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });

                    // Convierte el binario a un Blob para descargarlo
                    function s2ab(s) {
                        var buf = new ArrayBuffer(s.length);
                        var view = new Uint8Array(buf);
                        for (var i = 0; i < s.length; i++) {
                            view[i] = s.charCodeAt(i) & 0xFF;
                        }
                        return buf;
                    }

                    // Crea el Blob y dispara la descarga
                    var blob = new Blob([s2ab(wbout)], { type: 'application/octet-stream' });
                    var a = document.createElement('a');
                    a.href = URL.createObjectURL(blob);
                    a.download = config.filename || 'tabla_datos.xlsx';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                }
            });
        }
    };
}
