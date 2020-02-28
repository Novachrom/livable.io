document.getElementById('cost-filter-form').addEventListener("submit", function (e) {
    e.preventDefault();
    let fromValue = this.elements['cost_of_living_from'].value;
    let toValue = this.elements['cost_of_living_to'].value;
    if(fromValue && toValue) {
        let url = new URL(this.action);
        let params = new URLSearchParams(url.search.slice(1));
        params.append('cost_of_living_from', fromValue);
        params.append('cost_of_living_to', toValue);
        url.search = '&'+params.toString();
        document.location = url.toString();
    }

    return false;
});
