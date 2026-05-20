let a = document.querySelector('.dt-responsive');

if (a) {
  let table = new DataTable(a, {
    columnDefs: [
      {
        className: 'control',
        orderable: false,
        targets: 0,
        searchable: false,
        render: function () {
          return '';
        }
      },
      {
        targets: -1,
        render: function (data, type, row) {
          let estado = row[9]; // última columna (status)

          let estados = {
            1: {
              title: 'Current',
              class: 'bg-label-primary'
            },
            2: {
              title: 'Professional',
              class: 'bg-label-success'
            },
            3: {
              title: 'Rejected',
              class: 'bg-label-danger'
            },
            4: {
              title: 'Resigned',
              class: 'bg-label-warning'
            },
            5: {
              title: 'Applied',
              class: 'bg-label-info'
            }
          };

          return estados[estado]
            ? `<span class="badge ${estados[estado].class}">
               ${estados[estado].title}
             </span>`
            : data;
        }
      }
    ],

    destroy: true,

    layout: {
      topStart: {
        rowClass: 'row mx-3 my-0 justify-content-between',
        features: [
          {
            pageLength: {
              menu: [5, 10, 25, 50, 100],
              text: 'Mostrar _MENU_ registros'
            }
          }
        ]
      },
      topEnd: {
        features: [
          {
            search: {
              placeholder: ''
            }
          },
          {
            buttons: [
              {
                extend: 'collection',
                text: `
            <span class="d-flex align-items-center gap-2">
              <i class="icon-base bx bx-export me-sm-1"></i>
              <span class="d-none d-sm-inline-block">Exportar</span>
            </span>
          `,
                className: 'btn btn-label-primary dropdown-toggle',
                buttons: [
                  {
                    extend: 'print',
                    text: `
      <span class="d-flex align-items-center">
        <i class="icon-base bx bx-printer me-1"></i>Print
      </span>
    `,
                    className: 'dropdown-item',
                    exportOptions: {
                      columns: ':not(:last-child)'
                    }
                  },
                  {
                    extend: 'csv',
                    text: `
      <span class="d-flex align-items-center">
        <i class="icon-base bx bx-file me-1"></i>Csv
      </span>
    `,
                    className: 'dropdown-item',
                    exportOptions: {
                      columns: ':not(:last-child)'
                    }
                  },
                  {
                    extend: 'excel',
                    text: `
      <span class="d-flex align-items-center">
        <i class="icon-base bx bxs-file-export me-1"></i>Excel
      </span>
    `,
                    className: 'dropdown-item',
                    exportOptions: {
                      columns: ':not(:last-child)'
                    }
                  },
                  {
                    extend: 'pdf',
                    text: `
      <span class="d-flex align-items-center">
        <i class="icon-base bx bxs-file-pdf me-1"></i>Pdf
      </span>
    `,
                    className: 'dropdown-item',
                    exportOptions: {
                      columns: ':not(:last-child)'
                    }
                  },
                  {
                    extend: 'copy',
                    text: `
      <span class="d-flex align-items-center">
        <i class="icon-base bx bx-copy me-1"></i>Copy
      </span>
    `,
                    className: 'dropdown-item',
                    exportOptions: {
                      columns: ':not(:last-child)'
                    }
                  }
                ],
                init: function (api, node, config) {
                  // Removemos específicamente la clase que molesta
                  $(node).removeClass('btn-secondary');
                }
              }
            ]
          }
        ]
      },
      bottomStart: {
        rowClass: 'row mx-3 justify-content-between',
        features: ['info']
      },
      bottomEnd: {
        paging: {
          firstLast: false
        }
      }
    },

    language: {
      lengthMenu: 'Mostrar _MENU_ registros',
      zeroRecords: 'No se encontraron resultados',
      emptyTable: "No hay datos disponibles en la tabla",
      info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
      infoEmpty: 'Sin registros disponibles',
      infoFiltered: '(filtrado de _MAX_ registros)',
      search: 'Buscar:',
      paginate: {
        next: '<i class="icon-base bx bx-chevron-right scaleX-n1-rtl icon-sm"></i>',
        previous: '<i class="icon-base bx bx-chevron-left scaleX-n1-rtl icon-sm"></i>'
      }
    },

    responsive: {
      details: {
        display: DataTable.Responsive.display.modal({
          header: function (row) {
            // return 'Details of ' + row.data()[2]; // ahora es array, no objeto
            return 'Detalles'; // ahora es array, no objeto
          }
        }),
        type: 'column',
        renderer: function (api, rowIdx, columns) {
          let data = columns
            .map(function (col) {
              return col.title !== ''
                ? `<tr data-dt-row="${col.rowIndex}" data-dt-column="${col.columnIndex}">
                 <td>${col.title}:</td>
                 <td>${col.data}</td>
               </tr>`
                : '';
            })
            .join('');

          if (!data) return false;

          let container = document.createElement('div');
          container.classList.add('table-responsive');

          let table = document.createElement('table');
          table.classList.add('table');

          let tbody = document.createElement('tbody');
          tbody.innerHTML = data;

          table.appendChild(tbody);
          container.appendChild(table);

          return container;
        }
      }
    }
  });
  // table.buttons().container().addClass('d-none');

  // document.getElementById('btnExportar').addEventListener('click', function () {
  //   table.button('.buttons-collection').trigger();
  // });
  // table.on('init', function () {
  //   table.button('.buttons-collection').trigger();
  // });
}
