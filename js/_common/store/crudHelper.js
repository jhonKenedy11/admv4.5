import { mapActions, mapGetters } from "vuex";

export default MODULE_NAME => {
  /**
   * @typedef {object} TComputed
   * @property {{(): T[]}} items
   * Retorna a lista de items {T}
   * @property {{(): string}} selectedItem
   * Retorna o item selecionado {T}
   * @property {{(): boolean}} loading
   * Retorna true quando ha alguma operacao em andamento
   */
  /** @type {TComputed} */
  const computed = {
    ...mapGetters({
      items: `${MODULE_NAME}/items`,
      selectedItem: `${MODULE_NAME}/selectedItem`,
      loading: `${MODULE_NAME}/loading`,
      total: `${MODULE_NAME}/total`,
      from: `${MODULE_NAME}/from`,
      to: `${MODULE_NAME}/to`
      // totalPages: `${MODULE_NAME}/totalPages`
    })
  };
  /**
   * @typedef {object} TActions
   * @property {{() => Promise<void>}} find
   * Carrega a Lista de itens T na store
   * @property {{(page: number) => Promise<void>}} findPaginated
   * Carrega a Lista de itens T na store
   * @property {{() => Promise<void>}} params
   * Carrega a Lista de itens T na store, passando os parametros
   * @property {{({ id: any }): Promise<void>}} delete
   * Remove o item T
   * @property {{({ id: any }): Promise<void>}} select
   * Seleciona o objeto com id carregando-o e deixando-o disponivel em selectedItem
   * @property {{(object: T): Promise<void>}} upsert
   * Insere ou atualiza o elemento
   */
  /** @type {TActions} */
  const actions = {
    ...mapActions({
      find: `${MODULE_NAME}/find`,
      findPaginated: `${MODULE_NAME}/findPaginated`,
      params: `${MODULE_NAME}/params`,
      update: `${MODULE_NAME}/update`,
      delete: `${MODULE_NAME}/delete`,
      select: `${MODULE_NAME}/select`,
      upsert: `${MODULE_NAME}/upsert`,
      upsertPaginated: `${MODULE_NAME}/upsertPaginated`
    })
  };
  return {
    computed,
    actions
  };
};
