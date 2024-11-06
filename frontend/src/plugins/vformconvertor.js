export default function convert(data) {
    const keys = Object.keys(data.originalData)
    var result = {}
    keys.forEach(element => {
        if (element == 'leag_id') {
            if (data[element]._id) {
                data[element] = data[element]._id
            }
        }
        result[element] = data[element]
    });
    return result
}