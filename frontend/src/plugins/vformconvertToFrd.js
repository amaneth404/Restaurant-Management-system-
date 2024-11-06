export default function convert(data) {
    var formdata = new FormData()
    const keys = Object.keys(data.originalData)
    keys.forEach(element => {
        if (Array.isArray(data[element])) {
            for (var j = 0; j < data[element].length; j++) {
                formdata.append(element + '[' + j + ']', data[element][j])
            }
        } else {
            formdata.append(element, data[element])
        }
    });
    return formdata
}