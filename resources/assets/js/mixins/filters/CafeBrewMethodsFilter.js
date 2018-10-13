export const CafeBrewMethodsFilter = {
    methods: {
        processCafeBrewMethodsFilter(cafe, brewMethods) {
            // 如果冲泡方法不为空，则进行处理
            if (brewMethods.length > 0) {
                var cafeBrewMethods = [];

                // 将咖啡店所有冲泡方法都推送到 cafeBrewMethods 数组
                for (var i = 0; i < cafe.brew_methods.length; i++) {
                    cafeBrewMethods.push(cafe.brew_methods[i].method);
                }

                // 遍历所有待处理冲泡方法，如果在 cafeBrewMethods 数组中则返回 true
                for (var i = 0; i < brewMethods.length; i++) {
                    if (cafeBrewMethods.indexOf(brewMethods[i]) > -1) {
                        return true;
                    }
                }

                // 如果都不在 cafeBrewMethods 数组中则返回 false
                return false;

            } else {
                return true;
            }
        }
    }
};