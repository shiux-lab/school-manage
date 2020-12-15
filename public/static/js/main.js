const app = new Vue({
  el: "#app",
  data: {
    ajax: "ajax.php",
    key: false,
    list: [],
    keys: [],
    currentData: [],
    order: "",
    isClick: 0,
    type: "id",
    pages: {
      totalPage: 0,
      pageNum: 8,
      showPage: 6,
      currentPage: 1
    }
  },
  methods: {
    Toast: Swal.mixin({
      toast: true,
      position: "top-end",
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
      },
    }),
    sort(type) {
      this.isClick = !this.isClick;
      this.order = this.isClick ? "desc" : "";
      this.type = type;
      $.ajax({
        url: this.ajax,
        type: "POST",
        dataType: "json",
        data: {
          action: "selectAll",
          type: type,
          order: this.order,
        },
        success: (data) => {
          this.list = data;
          this.currentData = this.list.slice(
            (this.pages.currentPage - 1) * this.pages.pageNum,
            this.pages.currentPage * this.pages.pageNum
          );
          this.keys = [];
          this.currentData.forEach((item) => {
            this.keys.push({
              id: item.id,
              status: false,
            });
          });
        },
      });
    },
    onCheck(index) {
      this.keys[index].status = !this.keys[index].status;
    },
    addBtn() {
      Swal.fire({
        title: "<span><i class='fas fa-plus-square'></i> 添加成员</span>",
        html: `<div class="row">
                <label for="name" class="col-4 mb-2"><i class="fas fa-user"></i> 姓名：</label>
                <input type="text" id="name" class="col-8 form-control mb-2" name="name" placeholder="请输入成员姓名" required >
                <label for="sex" class="col-4 mb-2"><i class="fab fa-android"></i> 性别：</label>
                <select name="sex" id="sex" class="col-8 form-control mb-2"  required>
                    <option value="1">男</option>
                    <option value="0">女</option>
                </select>
                <label for="tel" class="col-4 mb-2"><i class="fas fa-phone"></i> 电话号码：</label>
                <input type="text" id="tel" class="col-8 form-control mb-2" name="tel" placeholder="请输入成员电话号码" required>
                <label for="room" class="col-4 mb-2"><i class="fas fa-door-closed"></i> 宿舍号：</label>
                <input type="text" id="room" class="col-8 form-control mb-2" name="room" placeholder="请输入成员宿舍号" required>
                <label for="major" class="col-4 mb-2"><i class="fas fa-project-diagram"></i> 专业：</label>
                <input type="text" id="major" class="col-8 form-control mb-2" name="major" placeholder="请输入成员专业" required>
              </div>`,
        showCloseButton: true,
        confirmButtonText: "提交",
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: this.ajax,
            type: "POST",
            data: {
              action: "add",
              name: $("#name").val(),
              sex: $("#sex").val(),
              tel: $("#tel").val(),
              room: $("#room").val(),
              major: $("#major").val()
            },
            success: (data) => {
              this.Toast.fire({
                title: data,
                icon: "success",
              });
              $.ajax({
                url: this.ajax,
                type: "POST",
                dataType: "json",
                data: {
                  action: "selectLastOne",
                },
                success: (data) => {
                  this.list.push(data);
                  if (this.pages.totalPage === this.pages.currentPage) {
                    this.currentData = this.list.slice(
                      (this.pages.currentPage - 1) * this.pages.pageNum,
                      this.pages.currentPage * this.pages.pageNum
                    );
                    this.keys.push({
                      id: data.id,
                      status: false,
                    });
                  }
                  if (this.list.length % this.pages.pageNum > 0)
                    this.pages.totalPage = Math.ceil(
                      this.list.length / this.pages.pageNum
                    );
                },
              });
            },
            error: data => {
              this.Toast.fire({
                title: data,
                icon: "error"
              });
            }
          });
        }
      });
    },
    delOneBtn(id,index) {
      Swal.fire({
        icon: 'question',
        title: '<span><i class="fas fa-trash"></i> 确认删除吗？</span>',
        html: '删除之后无法恢复。',
        showCancelButton: true,
        cancelButtonText: '取消',
        confirmButtonText: "确认",
        reverseButtons: true,
        focusCancel: true
      }).then(data => {
        if (data.isConfirmed) {
          $.ajax({
            url: this.ajax,
            type: 'POST',
            data: {
              action: 'delete',
              id: id
            },
            success: data => {
              this.Toast.fire({
                icon: 'success',
                title: data
              });
              this.list.some((item,index) =>{
                if(item.id===id){
                  this.list.splice(index, 1);
                  return true;
                }
              });
              this.delRefresh();
            },
            error: data => {
              this.Toast.fire({
                icon: 'error',
                title: data
              })
            }
          })
        }
      })
    },
    async delBtn() {
      if (this.keys.find((item) => item.status)) {
        let arr = [];
        this.keys.forEach((item) => item.status && arr.push(item.id));
        $.ajax({
          url: this.ajax,
          type: "POST",
          data: {
            action: "delMul",
            data: arr.join(","),
          },
          success: (data) => {
            this.Toast.fire({
              title: data,
              icon: "success",
            });
            this.list = this.list.filter((item) => {
              return !arr.includes(item.id);
            });
            this.delRefresh();
          },
          error: (data) => {
            this.Toast.fire({
              title: data,
              icon: "error",
            });
          },
        });
      } else {
        const { value: id } = await Swal.fire({
          icon: 'question',
          title: "<span><i class='fas fa-trash-alt'></i> 删除成员</span>",
          input: "number",
          inputLabel: "输入 成员id 删除成员",
          showCancelButton: true,
          cancelButtonText: '取消',
          confirmButtonText: "确认",
          reverseButtons: true,
          focusCancel: true,
          inputPlaceholder: "Id",
          inputAttributes: {
            min: 1,
          },
        });
        if (id) {
          $.ajax({
            url: this.ajax,
            type: "POST",
            data: {
              action: "delete",
              id: id,
            },
            success: (data) => {
              this.list.some((item,index) =>{
                if(item.id===id){
                  this.list.splice(index, 1);
                  return true;
                }
              });
              this.Toast.fire({
                title: data,
                icon: "success",
              });

              this.delRefresh();
            },
            error: (data) => {
              this.Toast.fire({
                title: data,
                icon: "error",
              });
            },
          });
        }
      }
    },
    allBtn() {
      this.key = !this.key;
      this.keys.forEach((item) => (item.status = this.key));
    },
    switchPage(index) {
      if (index === this.pages.currentPage) return;
      this.pages.currentPage = index;
      // this.currentData = this.list.filter((item, i) => i >= (index - 1) * this.pages.pageNum && i < index * this.pages.pageNum)
      this.currentData = this.list.slice(
        (index - 1) * this.pages.pageNum,
        index * this.pages.pageNum
      );
      this.keys = [];
      this.currentData.forEach((item) => {
        this.keys.push({
          id: item.id,
          status: false,
        });
      });
    },
    loginBtn() {
      Swal.fire({
        title: '管理员登录',
        html: `<div class="row">
            <label for="user" class="col-4 mb-2"><i class="fas fa-user"></i> 用户名：</label>
            <input type="text" id="user" class="col-8 form-control mb-2" name="username" placeholder="*用户名或邮箱"/>
            <label for="pass" class="col-4 mb-2"><i class="fas fa-key"></i> 密  码：</label>
            <input type="password" id="pass" class="col-8 form-control mb-2" name="password" placeholder="*密码" />
          </div>`,
        showCancelButton: true,
        reverseButtons: true,
        cancelButtonText: '取消',
        confirmButtonText: "确认"
      }).then(result=>{
        if (result.isConfirmed) {
          $.ajax({
            url: this.ajax,
            type: 'POST',
            data: {
              action: 'login',
              user: $("#user").val(),
              pass: $("#pass").val()
            },
            success: data => {
              this.Toast.fire({
                title: data,
                icon: 'success'
              })
              setInterval(location.reload(),3000);
            },
            error: data => {
              this.Toast.fire({
                title: data,
                icon: 'error'
              })
              setInterval(this.loginBtn(),3000);
            }
          })
        }
      })
    },
    logoutBtn() {
      $.ajax({
        url: this.ajax,
        type: 'POST',
        data: {
          action: 'logout'
        },
        success: (data) => {
          this.Toast.fire({
            title: data,
            icon: 'success'
          })
          setInterval(location.reload(),3000);
        },
        error: (data) => {
          this.Toast.fire({
            title: data,
            icon: 'error'
          })
        }
      })
    },
    alter(id,index) {
      $.ajax({
        url: this.ajax,
        type: 'POST',
        data: {
          id: id,
          action: 'selectIdOne'
        },
        dataType: 'json',
        success: data=>{
          Swal.fire({
            title: "<span><i class=\"fas fa-pencil-alt\"></i> 修改成员</span>",
            html: `<div class="row">
              <label for="name" class="col-4 mb-2"><i class="fas fa-user"></i> 姓名：</label>
              <input type="text" id="name" class="col-8 form-control mb-2" name="name" value="${data.name}" placeholder="请输入成员姓名" required >
              <label for="sex" class="col-4 mb-2"><i class="fab fa-android"></i> 性别：</label>
              <select name="sex" id="sex" class="col-8 form-control mb-2"  required>
                <option value="1" ${Number(data.sex)?'selected':''}>男</option>
                <option value="0" ${Number(data.sex)?'':'selected'}>女</option>
              </select>
              <label for="tel" class="col-4 mb-2"><i class="fas fa-phone"></i> 电话号码：</label>
              <input type="text" id="tel" class="col-8 form-control mb-2" name="tel" value="${data.tel}" placeholder="请输入成员电话号码" required>
              <label for="room" class="col-4 mb-2"><i class="fas fa-door-closed"></i> 宿舍号：</label>
              <input type="text" id="room" class="col-8 form-control mb-2" name="room" value="${data.room}" placeholder="请输入成员宿舍号" required>
              <label for="major" class="col-4 mb-2"><i class="fas fa-project-diagram"></i> 专业：</label>
              <input type="text" id="major" class="col-8 form-control mb-2" name="major" value="${data.major}" placeholder="请输入成员专业" required>
            </div>`,
            showCloseButton: true,
            confirmButtonText: "提交"
          }).then(result=>{
            if (result.isConfirmed) {
              const obj = {
                id: id,
                name: $("#name").val(),
                sex: $("#sex").val(),
                tel: $("#tel").val(),
                room: $("#room").val(),
                major: $("#major").val()
              };
              $.ajax({
                url: this.ajax,
                type: 'POST',
                data: Object.assign({action: 'alter'}, obj),
                success: (data) => {
                  this.Toast.fire({
                    title: data,
                    icon: "success",
                  });
                  Vue.set(this.list,index,obj);
                  if ((index < 8 && this.pages.currentPage === 1) || (Math.ceil(index / this.pageNum) === this.pages.currentPage)) {
                    this.currentData = this.list.slice(
                        (this.pages.currentPage - 1) * this.pages.pageNum,
                        this.pages.currentPage * this.pages.pageNum
                    );
                    Object.assign(this.keys[index],{status: false});
                  }
                },
                error: data => {
                  this.Toast.fire({
                    title: data,
                    icon: "error"
                  });
                }
              })
            }
          })
        }
      })
    },
    delRefresh() {
      if (this.pages.currentPage > 1) this.pages.currentPage--;
      if (this.currentData.length === 1)
        this.pages.totalPage = Math.ceil(
            this.list.length / this.pages.pageNum
        );
      this.currentData = this.list.slice(
          (this.pages.currentPage - 1) * this.pages.pageNum,
          this.pages.currentPage * this.pages.pageNum
      );
      this.keys = [];
      this.currentData.forEach((item) => {
        this.keys.push({
          id: item.id,
          status: false,
        });
      });
    }
  },
  mounted() {
    $.ajax({
      url: this.ajax,
      type: "POST",
      dataType: "json",
      data: {
        action: "selectAll",
        type: "id",
        order: "asc",
      },
      success: (data) => {
        this.list = data;
        this.pages.totalPage = Math.ceil(data.length / this.pages.pageNum);
        this.currentData = this.list.slice(0, this.pages.pageNum);
        this.currentData.forEach((item) => {
          this.keys.push({
            id: item.id,
            status: false,
          });
        });
      },
    });
  },
});
