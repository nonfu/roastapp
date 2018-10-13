export const CafeTextFilter = {
    methods: {
        processCafeTextFilter(cafe, text) {
            // 文本不为空时才会处理
            if (text.length > 0) {
                // 如果咖啡店名称、位置、地址或城市与给定文本匹配，则返回 true，否则返回 false
                if (cafe.name.toLowerCase().match('[^,]*' + text.toLowerCase() + '[,$]*')
                    || cafe.location_name.toLowerCase().match('[^,]*' + text.toLowerCase() + '[,$]*')
                    || cafe.address.toLowerCase().match('[^,]*' + text.toLowerCase() + '[,$]*')
                    || cafe.city.toLowerCase().match('[^,]*' + text.toLowerCase() + '[,$]*')
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