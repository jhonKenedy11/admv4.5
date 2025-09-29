/** @param {BaseService} Service */
const CRUD = Service => {
  /** @template T */
  const state = {
    /**
     * @type {T[]}
     * Lista de todos os {T}
     */
    items: [],
    /**
     * @type {T}
     * Item que se está trabalhando
     */
    selectedItem: undefined,
    page: 1,
    total: 0,
    from: 0,
    to: 0,
    lastPage: 0,
    loading: false
  };

  const getters = {
    items: state => state.items,
    selectedItem: state => state.selectedItem,
    loading: state => state.loading,
    page: state => state.page,
    from: state => state.from,
    to: state => state.to,
    lastPage: state => state.lastPage,
    total: state => state.total
  };

  /** @type {import("vuex").MutationTree<typeof state>} */
  const mutations = {
    SET_ITEMS(state, payload) {
      state.items = payload;
    },
    SET_SELECTED_ITEM(state, payload) {
      state.selectedItem = payload;
    },
    SET_LOADING(state, payload) {
      state.loading = payload;
    },
    SET_PAGE(state, payload) {
      state.page = payload;
    },
    SET_FROM(state, payload) {
      state.from = payload;
    },
    SET_TO(state, payload) {
      state.to = payload;
    },
    SET_TOTAL(state, payload) {
      state.total = payload;
    }
  };

  /** @type {import("vuex").ActionTree<typeof state>} */
  const actions = {
    /**
     * Buscar lista de itens e adicionar o resultado na state list
     * @param {*} context
     */

    setLoading(context) {
      context.commit("SET_LOADING", true);
    },
    find(context) {
      context.commit("SET_LOADING", true);
      return Service.find()
        .then(response => context.commit("SET_ITEMS", response.data))
        .finally(() => context.commit("SET_LOADING", false));
    },
    /**
     * Buscar lista de itens e adicionar o resultado na state list
     * @param {*} context
     */

    findPaginated(context, { page }) {
      page = page || 1;

      const query = {
        page
      };
      if (name) {
        query.name = name;
      }
      if (nome) {
        query.nome = nome;
      }
      if (descricao) {
        query.descricao = descricao;
      }
      context.commit("SET_LOADING", true);
      context.commit("SET_PAGE", page);
      return Service.find(query)
        .then(response => {
          context.commit("SET_TOTAL", response.data.total);
          context.commit("SET_FROM", response.data.from);
          context.commit("SET_TO", response.data.to);
          context.commit("SET_ITEMS", response.data.data);
        })
        .finally(() => context.commit("SET_LOADING", false));
    },
    /**
     * Executa o find passando parametros de query na rota T/params
     * @param {*} context
     */
    params(context, payload) {
      context.commit("SET_LOADING", true);
      return Service.params(payload)
        .then(response => context.commit("SET_ITEMS", response.data))
        .finally(() => context.commit("SET_LOADING", false));
    },
    search(context, payload) {
      context.commit("SET_LOADING", true);
      return Service.search(payload)
        .then(response => response.data)
        .finally(() => context.commit("SET_LOADING", false));
    },
    /**
     *  Enviar requisição para adicionar um novo item na base
     * @param {*} context
     * @param {T} payload
     */
    create(context, payload) {
      context.commit("SET_LOADING", true);
      return Service.create(payload)
        .then(() => context.dispatch("find"))
        .finally(() => context.commit("SET_LOADING", false));
    },
    /**
     *  Enviar requisição para atualizar ou inserir item na base
     * @param {*} context
     * @param {T} payload
     */
    upsert(context, payload) {
      context.commit("SET_LOADING", true);
      let promise = null;
      if (payload.id) {
        const { id } = payload;
        delete payload.id;
        promise = Service.update(id, payload);
      } else {
        promise = Service.create(payload);
      }
      return promise
        .then(() => context.dispatch("find"))
        .finally(() => context.commit("SET_LOADING", false));
    },
    /**
     * Enviar requisição para atualizar ou inserir item na base, onde payload contem informações
     * de repaginação.
     * @param {*} context
     * @param {T} payload Payload contendo os campos { item, query }
     */
    upsertPaginated(context, { item, query }) {
      context.commit("SET_LOADING", true);
      let promise = null;
      if (item.id) {
        const { id } = item;
        delete item.id;
        promise = Service.update(id, item);
      } else {
        promise = Service.create(item);
      }
      return promise
        .then(() => context.dispatch("findPaginated", query))
        .finally(() => context.commit("SET_LOADING", false));
    },
    /**
     * Enviar requisição para atualizar um item na base
     * O payload precisa conter 2 objetos, um com o id do item que está sendo editado, outro com os dados
     * @param {*} context
     * @param {*} payload
     */
    update(context, { id, data }) {
      context.commit("SET_LOADING", true);
      return Service.update(id, data)
        .then(() => context.dispatch("find"))
        .finally(() => context.commit("SET_LOADING", false));
    },
    /**
     * Remover o item {T}
     * @param {*} context
     * @param {T} payload
     */
    delete(context, { id }) {
      console.log(id);
      context.commit("SET_LOADING", true);
      return Service.delete(id)
        .then(() => context.dispatch("find"))
        .finally(() => context.commit("SET_LOADING", false));
    },
    /**
     *  Seleciona um item {T}
     * @param {*} context
     * @param {T} payload
     */
    select(context, { id }) {
      context.commit("SET_LOADING", true);
      return Service.findOne(id)
        .then(response => response.data)
        .then(data => {
          Object.keys(data).find(key => key == "specialFlow")
            ? (data.specialFlow = data.specialFlow == 1 ? true : false)
            : null;
          context.commit("SET_SELECTED_ITEM", data);
          return data;
        })
        .finally(() => context.commit("SET_LOADING", false));
    }
  };

  return {
    state,
    getters,
    mutations,
    actions
  };
};

export { CRUD };
