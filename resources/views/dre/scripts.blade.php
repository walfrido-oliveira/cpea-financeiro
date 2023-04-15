<script>
    window.addEventListener("load", function() {

        let array = new Array;
        var fetches = [];

        function getTotal() {

            document.querySelectorAll(".total-classification").forEach((item) => {
                const dataForm = new FormData();
                const token = document.querySelector('meta[name="csrf-token"]').content;

                dataForm.append('id', item.dataset.id);
                dataForm.append('year', item.dataset.year);
                @foreach ($months as $key => $month)
                    dataForm.append('months[{!! $key !!}]', '{!! $month !!}');
                @endforeach
                dataForm.append('_method', 'POST');
                dataForm.append('_token', token);

                fetches.push(
                    fetch('{{ route('dre.total') }}', {
                        method: 'POST',
                        body: dataForm
                    })
                    .then(res => res.text())
                    .then(data => {
                        item.querySelectorAll(".total-classification-result").forEach(item2 => {
                            item2.innerHTML = JSON.parse(data)[item2.dataset.month];
                            item2.classList.remove("disablecel");
                            item2.querySelector("a").addEventListener("click", function(
                                e) {
                                e.preventDefault();
                                editDre(this);
                            });
                        });
                        array.push(data);
                    }).catch(status, err => {
                        console.log(err);
                    })
                );
            });
        }

        getTotal();

        Promise.all(fetches).then(function() {
            getAmount();
            getRL();
            getNSR();
        });

        function getRL() {
            document.querySelectorAll(".rl").forEach(item => {
                const dataForm = new FormData();
                const token = document.querySelector('meta[name="csrf-token"]').content;

                dataForm.append('id', item.dataset.id);
                dataForm.append('year', item.dataset.year);
                dataForm.append('_method', 'POST');
                dataForm.append('_token', token);

                fetch('{{ route('dre.rl') }}', {
                        method: 'POST',
                        body: dataForm
                    })
                    .then(res => res.text())
                    .then(data => {
                        item.innerHTML = JSON.parse(data);
                        item.classList.remove("disablecel");
                    }).catch(err => {
                        console.log(err);
                    });
            });
        }

        function getNSR() {
            document.querySelectorAll(".nsr").forEach(item => {
                const dataForm = new FormData();
                const token = document.querySelector('meta[name="csrf-token"]').content;

                dataForm.append('id', item.dataset.id);
                dataForm.append('year', item.dataset.year);
                dataForm.append('_method', 'POST');
                dataForm.append('_token', token);

                fetch('{{ route('dre.nsr') }}', {
                        method: 'POST',
                        body: dataForm
                    })
                    .then(res => res.text())
                    .then(data => {
                        item.innerHTML = JSON.parse(data);
                        item.classList.remove("disablecel");
                    }).catch(err => {
                        console.log(err);
                    });
            });
        }

        function getAmount() {
            document.querySelectorAll(".amount").forEach(item => {
                const dataForm = new FormData();
                const token = document.querySelector('meta[name="csrf-token"]').content;

                dataForm.append('id', item.dataset.id);
                dataForm.append('year', item.dataset.year);
                dataForm.append('_method', 'POST');
                dataForm.append('_token', token);

                fetch('{{ route('dre.amount') }}', {
                        method: 'POST',
                        body: dataForm
                    })
                    .then(res => res.text())
                    .then(data => {
                        item.innerHTML = JSON.parse(data);
                        item.classList.remove("disablecel");
                    }).catch(err => {
                        console.log(err);
                    });
            });
        }
    });
</script>

<script>
    window.addEventListener("load", function() {
        document.querySelector("#scroll_top div").style.width = document.querySelector(
            "#accounting_classifications_table").clientWidth + 'px';
    });
    var wrapper1 = document.getElementById('scroll_top');
    var wrapper2 = document.getElementById('scroll_bottom');
    wrapper1.onscroll = function() {
        wrapper2.scrollLeft = wrapper1.scrollLeft;
    };
    wrapper2.onscroll = function() {
        wrapper1.scrollLeft = wrapper2.scrollLeft;
    };
</script>

<script>
    window.addEventListener("load", function() {
        document.getElementById("search").addEventListener("click", function() {
            document.getElementById("search_year_form").submit();
            document.getElementById("spin_load").classList.remove("hidden");
        });
        document.getElementById("spin_load").classList.add("hidden");
    });
</script>

<script>
    function editDre(elem) {
        var modal = document.getElementById("dre_modal");
        modal.classList.remove("hidden");
        modal.classList.add("block");

        document.querySelector("#dre_modal #value").value = "";
        document.querySelector("#dre_modal #justification").value = "";
        document.querySelector("#dre_modal #accounting_classification_id").value = elem.dataset.id;
        document.querySelector("#dre_modal #month").value = elem.dataset.month;
        document.querySelector("#dre_modal #year").value = elem.dataset.year;
        document.querySelector("#dre_modal #value").value = elem.dataset.value;
        document.querySelector("#dre_modal #justification").value = elem.dataset.justification;
        var deleteDre = document.querySelector("#dre_modal #dre_delete");

        if (elem.dataset.destroy != 1) {
            deleteDre.classList.add("hidden");
        } else {
            deleteDre.dataset.url = deleteDre.dataset.url.replace("#", elem.dataset.dre);
            deleteDre.classList.remove("hidden");
        }
    }

    function deleteDre(elem) {
        var modalDre = document.getElementById("dre_modal");
        modalDre.classList.add("hidden");

        var url = elem.dataset.url;
        var modal = document.getElementById("delete_dre_modal");
        modal.dataset.url = url;
        modal.classList.remove("hidden");
        modal.classList.add("block");
    }

    document.querySelector("#dre_modal #dre_delete").addEventListener("click", function(e) {
        deleteDre(this);
    });

    document.getElementById("dre_cancel_modal").addEventListener("click", function(e) {
        var modal = document.getElementById("dre_modal");
        modal.classList.add("hidden");
    });

    document.getElementById("dre_confirm_modal").addEventListener("click", function(e) {
        document.getElementById("spin_load").classList.remove("hidden");

        let ajax = new XMLHttpRequest();
        let token = document.querySelector('meta[name="csrf-token"]').content;
        let method = 'POST';
        let value = document.querySelector("#dre_modal #value").value;
        let justification = document.querySelector("#dre_modal #justification").value
        let accounting_classification_id = document.querySelector("#dre_modal #accounting_classification_id")
            .value
        let month = document.querySelector("#dre_modal #month").value
        let year = document.querySelector("#dre_modal #year").value
        let url = "{!! route('dre.create') !!}";

        ajax.open("POST", url);

        ajax.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var resp = JSON.parse(ajax.response);
                document.getElementById("spin_load").classList.add("hidden");
                toastr.success(resp.message);

                const totalClassification = document.querySelector(
                    `.total-classification-result[data-month='${month}'][data-year='${year}'][data-id='${accounting_classification_id}']`
                    );
                totalClassification.innerHTML = resp.renderized;

                totalClassification.querySelector("a").addEventListener("click", function(e) {
                    e.preventDefault();
                    editDre(this);
                });

                var modal = document.getElementById("dre_modal");
                modal.classList.add("hidden");

            } else if (this.readyState == 4 && this.status != 200) {
                document.getElementById("spin_load").classList.add("hidden");
                toastr.error("{!! __('Um erro ocorreu ao solicitar a consulta') !!}");
            }
        }

        var data = new FormData();
        data.append('_token', token);
        data.append('_method', method);
        data.append('value', value);
        data.append('justification', justification);
        data.append('month', month);
        data.append('year', year);
        data.append('accounting_classification_id', accounting_classification_id);

        ajax.send(data);

    });
</script>
