export const CafeIsRoasterFilter = {
    methods: {
        processCafeIsRoasterFilter(cafe) {
            // 检查咖啡店是否是自家烘焙
            if (cafe.roaster === 1) {
                return true;
            } else {
                return false;
            }
        }
    }
};