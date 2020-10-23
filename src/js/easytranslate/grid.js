varienGrid.prototype.easyTranslateRowClickCallback = function (grid, event) {
    let checkbox = event.target;
    if (!checkbox.matches('input')) {
        // toggle the checkbox manually on row click
        checkbox = this.retrieveCheckbox(checkbox);
        checkbox.checked = !checkbox.checked;
    }
    this.updateProjectEntities(checkbox.value, checkbox.checked);
};

varienGrid.prototype.retrieveCheckbox = function (element) {
    let row = element;
    while (!row.matches('tr')) {
        row = element.parentNode;
    }
    return row.querySelector('.in-project');
};

varienGrid.prototype.updateProjectEntities = function (entityId, included) {
    const separator = ',';
    const entityElement = this.getRelatedInputField();
    const entities = entityElement.value;
    let entityArray = (entities === '') ? [] : entities.split(separator);
    if (included && !entityArray.includes(entityId)) {
        entityArray.push(entityId);
    } else if (!included && entityArray.includes(entityId)) {
        const index = entityArray.indexOf(entityId);
        entityArray.splice(index, 1);
    }
    entityElement.value = entityArray.join(separator)
};

varienGrid.prototype.getRelatedInputId = function () {
    return 'included_' + this.containerId;
};

varienGrid.prototype.getRelatedInputField = function () {
    return document.getElementById(this.getRelatedInputId());
};

varienGrid.prototype.reload = varienGrid.prototype.reload.wrap(function (parentMethod, url) {
    const key = this.getRelatedInputId();
    const value = this.getRelatedInputField().value;
    if (!this.reloadParams) {
        this.reloadParams = {};
        this.reloadParams[key] = value;
    } else {
        this.reloadParams[key] = value;
        this.reloadParams.included_products = this.getRelatedInputField().value;
    }
    parentMethod(url);
});
