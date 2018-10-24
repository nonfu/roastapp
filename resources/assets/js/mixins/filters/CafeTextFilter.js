export const CafeTextFilter = {
    methods: {
        processCafeTextFilter(cafe, text) {
            // 文本不为空时才会处理
            if (text.length > 0) {
                // 如果咖啡店名称、位置、地址或城市与给定文本匹配，则返回 true，否则返回 false
                if (cafe.company.name.match('[^,]*' + text + '[,$]*')
                    || cafe.location_name.match('[^,]*' + text + '[,$]*')
                    || cafe.address.match('[^,]*' + text + '[,$]*')
                    || cafe.city.match('[^,]*' + text + '[,$]*')
                ) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        }
    }
};