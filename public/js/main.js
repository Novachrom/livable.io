document.getElementById('cost-filter-form').addEventListener("submit", function (e) {
    e.preventDefault();
    let allowedFields = ['cost_of_living_from', 'cost_of_living_to', 'sort_by'];
    let url = new URL(this.action);
    let params = new URLSearchParams(url.search.slice(1));
    for (let field of allowedFields) {
        let value = this.elements[field].value;
        if(value) {
            params.append(field, value);
        } else {
            params.delete(field);
        }
    }
    url.search = '&'+params.toString();
    document.location = url.toString();

    return false;
});
