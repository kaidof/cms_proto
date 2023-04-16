console.log('######################## ADMIN.JS')

/*
function fetchFromApi(url, dataObj) {
  return fetch(`${API_HOST}/${url}`, dataObj)
    .then(response => {
      if (response.ok) {
        return response.json()
      } else {
        return response.json().then(json => {
          throw new Error(json.message)
        })
      }
    })
}
*/


var Core = {
  apiUrl: '',
  setApiUrl: function (url) {
    this.apiUrl = url;
  },
  getData: function () {
    // Use this.apiUrl to make API requests
    // Example: fetch(this.apiUrl + '/data')
  }
};


document.querySelectorAll('.data-table a.delete').forEach(function (deleteLink) {
  // add click event listener
  deleteLink.addEventListener('click', function (e) {
    e.preventDefault();
    if (confirm('Are you sure?')) {
      // fetch from API
      fetch(deleteLink.getAttribute('href'), {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json'
        },
      })
        .then(function (response) {
          // reload page
          window.location.reload();
        })
        .catch(function (error) {
          console.log(error);
        });
    }
  });
})


