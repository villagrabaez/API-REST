$('#loadBooks').click(function(){
  $('#messages').first('p').text('Cargando libros...')
  $('#messages').show()

  $.ajax({
        'url': 'http://localhost:8000/books',
        'method': 'GET',
        'success': function (data) {
          $('#messages').hide();
          $('#booksTable > tbody').empty();
          for (b in data) {
            addBook(data[b]);
          }
          $('#formBook').show();
        },
        'error': function() {
          console.log('Error!!')
        }
    });
});

function addBook(book) {
  $('#booksTable tr:last').after('<tr><td>' + book.titulo + '</td><td>' + book.id_autor + '</td><td>' + book.id_genero + '</td></tr>')
}