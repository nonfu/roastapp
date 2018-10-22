export const CafeUserLikeFilter = {
    methods: {
        processCafeUserLikeFilter(cafe) {
            if (cafe.user_like_count === 1) {
                return true;
            } else {
                return false;
            }
        }
    }
};