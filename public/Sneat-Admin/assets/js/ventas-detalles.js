let tabla_detalles = document.querySelector('.datatables-order-details');

if (tabla_detalles) {
  let table = new DataTable(tabla_detalles, {
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
      topStart: { features: {} },
      topEnd: { features: {} },
      bottomStart: { rowClass: 'mt-0', features: [] },
      bottomEnd: {}
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
