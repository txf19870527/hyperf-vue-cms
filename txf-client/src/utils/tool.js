export function errMsg(component, error) {
    component.$message.error(error);

    if(error.code == -16) {
        component.$router.push("/login", onComplete => {}, onAbort => {})
    }
    
}