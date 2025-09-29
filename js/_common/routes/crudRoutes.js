const crudRoutes = (title, name, icon) => {
  // var titleTratado = title.substring(0,9) == 'Todos os ' ?
  //   (title.substring(9).charAt(0).toUpperCase() + title.substring(10).toLowerCase())
  //     : (title.charAt(0).toUpperCase() + title.substring(1).toLowerCase())
  return [
    {
      path: `${name}_list`,
      name: `${title}`,
      component: () => import(`@/modules/${name}/views/index`),
      meta: { title: title, noCache: true, icon: icon }
    },
    {
      path: `${name}_new`,
      name: `${title}`,
      hidden: true,
      component: () => import(`@/modules/${name}/views/upsert`),
      meta: { title: `Cadastro de ${title}`, noCache: true }
    },
    {
      path: `${name}_edit/:id`,
      name: `${title}`,
      hidden: true,
      component: () => import(`@/modules/${name}/views/upsert`),
      meta: { title: `Editar ${title}`, noCache: true }
    }
  ];
};

export { crudRoutes };
