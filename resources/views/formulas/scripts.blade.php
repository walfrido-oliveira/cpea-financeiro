<script>
    document.getElementById("btn_accounting_classification_formula_add").addEventListener("click", function() {
        let textAtea = document.getElementById("formula");
        let classification = document.getElementById("accounting_classification_formula")
        textAtea.value += classification.value;
    });

    document.getElementById("btn_accounting_classification_conditional_add").addEventListener("click", function() {
        let textAtea = document.getElementById("conditional");
        let classification = document.getElementById("accounting_classification_conditional")
        textAtea.value += classification.value;
    });

    document.getElementById("btn_accounting_classification_conditional_formula_add").addEventListener("click", function() {
        let textAtea = document.getElementById("conditional_formula");
        let classification = document.getElementById("accounting_classification_conditional_formula")
        textAtea.value += classification.value;
    });


    document.querySelector(".btn-outline-success").addEventListener("click", function() {
        let element1 = document.getElementById("accounting_classification_id");
        let element2 = document.getElementById("type_classification");

        element1.style.display = "block";
        element1.style.position = "absolute";

        element2.style.display = "block";
        element2.style.position = "absolute";

    });
</script>
