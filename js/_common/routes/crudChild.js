
const crudRoutes = (title, name, icon) => {  
  var titleTratado = title.substring(0,9) == 'Todos os ' ? 
    (title.substring(9).charAt(0).toUpperCase() + title.substring(10).toLowerCase())
      : (title.charAt(0).toUpperCase() + title.substring(1).toLowerCase())
  return [
    {
      path: `/${name}/list`,
      name: `${name}_list`,
      component: () => import(`@/modules/${name}/views/index`),
      meta: { title: title, noCache: true, icon: icon }
    },
    {
      path: `/${name}/new`,
      name: `${name}_new`,
      hidden: true,
      component: () => import(`@/modules/${name}/views/upsert`),
      meta: { title: `Cadastro de ${titleTratado}`, noCache: true }
    },
    {
      path: `/${name}/:id`,
      name: `${name}_edit`,
      hidden: true,
      component: () => import(`@/modules/${name}/views/upsert`),
      meta: { title: `Editar ${titleTratado}`, noCache: true }
    }
  ]
}

export { crudRoutes }

