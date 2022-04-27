<script>
    document.getElementById("btn_accounting_classification_formula_add").addEventListener("click", function() {
        let textAtea = document.getElementById("formula");
        let classification = document.getElementById("accounting_classification_formula")
        textAtea.value += classification.value;
    });
</script>
