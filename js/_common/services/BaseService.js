import axios from "axios";
/** @type {BaseService} */
export default class BaseService {
  constructor(api) {
    //http://cleaner.admservice.com.br/api/v1
    // this.api = "${process.env.BASE_API}${api}";
    this.api = `http://localhost:3000/${api}`;
    this.axios = axios;
  }

  /**
   * Listar todos os itens
   */
  find(obj) {
    if (obj) {
      const query = Object.keys(obj)
        .map(k => `${k}=${encodeURIComponent(obj[k])}`)
        .join("&");
      // console.log(`${this.api}`);
      return this.axios.get(`${this.api}?${query}`);
    } else {
      // console.log(`${this.api}`);
      return this.axios.get(`${this.api}`);
    }
  }

  /**
   * Custom Post
   */
  post(url, obj) {
    console.log(obj);
    return this.axios.post(`${this.api}/${url}`, obj);
  }

  /**
   * Listar todos os itens
   */
  params(obj) {
    const query = Object.keys(obj)
      .map(k => "${k}=${encodeURIComponent(obj[k])}")
      .join("&");
    return this.axios.get(`${this.api}/param?${query}`);
  }

  /**
   * Listar todos os itens
   */
  search(obj) {
    const query = Object.keys(obj)
      .map(k => "${k}=${encodeURIComponent(obj[k])}")
      .join("&");
    return this.axios.get(`${this.api}/search?${query}`);
  }

  /**
   *  Selecionar o equivalente ao id passado
   * @param {*} id
   */
  findOne(id) {
    return this.axios.get(`${this.api}/${id}`);
  }

  /**
   *  Inserir um novo item
   * @param {*} data
   */
  create(data) {
    console.log(this.api);
    console.log(data);
    return this.axios.post(`${this.api}`, data);
    // axios
    //   .post(`${this.api}`, data)
    //   .then(response => (this.info = response.data))
    //   .catch(error => {
    //     console.log("errooooodasdsadasdasdsa");
    //     console.log(
    //       error["response"]["data"]["error"]["details"][0]["message"]
    //     );
    //     return error["response"]["data"]["error"]["details"][0]["message"];
    //   });
  }

  /**
   *  Atualizar um item
   * @param {*} data
   */
  update(id, data) {
    return this.axios.put(`${this.api}/${id}`, data);
  }

  /**
   * Remover um item
   * @param {*} id
   */
  delete(id) {
    return this.axios.delete(`${this.api}/${id}`);
  }
}
