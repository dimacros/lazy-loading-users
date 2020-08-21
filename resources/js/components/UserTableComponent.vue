<template>
    <div class="container">
        <div class="row justify-content-center">
            <p>Mostrando la página {{ pagination.pageNumber }} de un total de {{ pagination.total }} registros.</p>
            <div class="col-md-10">
                <select v-model="status" class="custom-select" @change="onChangeStatus">
                    <option value="" selected>Seleccione un filtro</option>
                    <option value="active">Activo</option>
                    <option value="inactive">Inactivo</option>
                </select>
                <hr>
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Nombre</th>
                      <th scope="col">Correo</th>
                      <th scope="col">Estado</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-bind:key="user.id" v-for="user in users">
                      <th scope="row">{{ user.id }}</th>
                      <td>{{ user.name }}</td>
                      <td>{{ user.email }}</td>
                      <td>{{ user.status }}</td>
                    </tr>
                  </tbody>
                </table>
            </div>
            <nav>
              <ul class="pagination">
                <li class="page-item">
                  <a class="page-link" @click.prevent="() => onChangePage(links.firstPage)" :href="links.firstPage">&laquo; Primera Página</a>
                </li>
                <li class="page-item" :class="{ disabled: !links.previousPage }">
                  <a class="page-link" @click.prevent="() => onChangePage(links.previousPage)" :href="links.previousPage">Anterior</a>
                </li>
                <li class="page-item disabled">
                  <a class="page-link">{{ pagination.pageNumber }}</a>
                </li>
                <li class="page-item" :class="{ disabled: !links.nextPage }">
                  <a class="page-link" @click.prevent="() => onChangePage(links.nextPage)" :href="links.nextPage">Siguiente</a>
                </li>
                <li class="page-item">
                  <a class="page-link" @click.prevent="() => onChangePage(links.lastPage)" :href="links.lastPage">Última Página &raquo;</a>
                </li>
              </ul>
            </nav>
        </div>
    </div>
</template>

<script>
    export default {
      data () {
        return {
          status: null,
          users: [],
          links: {
            firstPage: null,
            lastPage: null,
            previousPage: null,
            nextPage: null
          },
          pagination: { 
            pageNumber: null, 
            total: null 
          }
        }
      },
      methods: {
        handleResult (response) {
          const results = response.data;
          this.users = results.data;
          this.links = {
            firstPage: results.first_page_url,
            lastPage: results.last_page_url,
            previousPage: results.prev_page_url,
            nextPage: results.next_page_url
          }
          this.pagination = { pageNumber: results.current_page, total: results.total }
        },
        fetchUsers (params) {
          axios.get('/api/users', { params }).then(this.handleResult)
        },
        onChangePage (url) {
          axios.get(url).then(this.handleResult)
        },
        onChangeStatus: function () {
          this.fetchUsers({ page: 1, status: this.status })
        }
      },
      mounted() {
        this.fetchUsers({ page: 1 })
      }
    }
</script>
