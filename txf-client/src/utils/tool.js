export function errMsg(component, error) {
    component.$message.error(error);

    if(error.code == 1015) {
        component.$router.push("/login", onComplete => {}, onAbort => {})
    }
    
}