import error from "@/modules/_common/mixins/errorLoopBack";

export default {
  methods: {
    /**
     * Redireciona para a rota para criação novo item
     */
    handleCreate(route) {
      this.$router.push(route);
    },
    /**
     * Redireciona para a rota de edicao do item
     * @param {object} item item a ser editado
     */
    handleUpdateItem(rota, item) {
      this.$router.push(`${rota + "_edit"}/${item.id}`);
    },
    handleUpdate(item) {
      console.log(`ID = ${item.ID}`);
      var msg = confirm("Deseja alterar este item?");
      msg
        ? this.update(`${item.ID}`, item)
          .then(() =>
            this.$notify({
              // title: "Item Alterado com Sucesso!",
              message: "Item Alterado com Sucesso!",
              type: "success",
              duration: 2000
            })
          )
          .catch(e =>
            this.$notify({
              //   title: log,
              message: e.toString(),
              type: "danger"
            })
          )
        : false;

      // this.$router.push(`${item.id}`);
    },
    /**
     * Remove o item, necessita da confirmacao do usuario
     * @param {object} item item a ser removido
     */
    handleDelete(item) {
      var msg = confirm("Deseja remover este item?");
      msg
        ? this.delete({ id: `${item.id}` })
          .then(() =>
            this.$notify({
              // title: "Item excluido",
              message: "Item excluido",
              type: "success",
              duration: 2000
            })
          )
          .then(() => this.$router.go())
          .catch(e =>
            this.$notify({
              //   title: log,
              message: e.toString(),
              type: "danger"
            })
          )
        : false;
      // this.$confirm("Deseja remover este item?", "Excluir")
      //   .then(ok => (ok ? this.delete(item) : false))
      //   .then(() =>
      //     this.$notify({
      //       title: "Item excluido",
      //       message: "OK",
      //       type: "success"
      //     })
      //   )
      //   .catch(console.log);
    },
    /**
     * Valida e submete o formulario $refs['dataForm'], utiliza o this.item para enviar para upsert
     */
    submit(item) {
      console.log("SubmitMixin");
      console.log(item);
      var msg = confirm(
        item.id == null
          ? "Deseja incluir este item?"
          : "Deseja alterar este item?"
      );
      msg
        ? this.upsert(item)
          .then(() =>
            this.$notify({
              message:
                item.id == null
                  ? "Cadastrado com Sucesso!"
                  : "Editado com sucesso!",
              type: "success",
              duration: 3000
            })
          )
          .then(() => this.$router.go(-1))
          .catch(e => {
            this.$notify({
              //   title: log,
              message: error.msgerro(e),
              type: "danger",
              duration: 4000
            });
          })
        : false;
      // this.$refs["dataForm"].clearValidate();
      // this.$refs["dataForm"].validate(valid => {
      //   if (valid) {
      //     this.upsert(this.item)
      //       .then(() =>
      //         this.$notify({
      //           title: "Item atualizado",
      //           message: "OK",
      //           type: "success",
      //           duration: 2000
      //         })
      //       )
      //       .then(() => this.$router.go(-1))
      //       .catch(err =>
      //         this.$notify({
      //           title: "Ocorreu um erro",
      //           message: err,
      //           type: "error",
      //           duration: 2000
      //         })
      //       );
      //   } else {
      //     this.$notify({
      //       title: "Revise os campos",
      //       message: "Erro na validacao",
      //       type: "error",
      //       duration: 2000
      //     });
      //   }
      // });
    },
    // Valida os campos que estão como required
    validaInputs(inputs) {
      for (let index = 0; index < inputs.length; index++) {
        if (inputs[index].required && inputs[index].value == "") {
          this.$notify({
            message: "Campo " + inputs[index].label + " é obrigatório.",
            type: "danger",
            duration: 2000
          });
          return false;
        }
      }
      return true;
    }
  }
};
