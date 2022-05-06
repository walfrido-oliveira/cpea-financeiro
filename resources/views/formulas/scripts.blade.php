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
</script>
